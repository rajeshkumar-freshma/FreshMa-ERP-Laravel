<?php

namespace App\Models;

use App\Core\CommonComponent;
use App\Core\Traits\SpatieLogsActivity;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    protected $appends = ['image_full_url', 'status_text', 'status_color_code', 'payment_status_text', 'payment_status_color_code'];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->updated_by = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->updated_by = Auth::user()->id;
            } else {
                $data->updated_by = 1;
            }
        });
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function transport_details()
    {
        return $this->hasMany(TransportTracking::class, 'sales_order_id', 'id');
    }

    public function expense_details()
    {
        return $this->hasMany(SalesExpense::class, 'sales_order_id', 'id');
    }

    public function product_details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id', 'id');
    }
    // public function product_price_history()
    // {
    //     return $this->hasOne(ProductPriceHistory::class, 'product_id', 'id');
    // }
    // public function sales_orders()
    // {
    //     // Assuming there is a relationship between ProductPriceHistory and SalesOrder
    //     return $this->product_price_history->hasMany(SalesOrder::class, 'product_id', 'product_id');
    // }

    public function user_details()
    {
        return $this->hasOne(User::class, 'id', 'vendor_id');
    }

    public function expense_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 1], ['type', 2]]);
    }

    public function transporttracking_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 2], ['type', 2]]);
    }

    public function machine_details()
    {
        return $this->hasOne(MachineData::class, 'id', 'machine_id');
    }

    public function sales_order_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')->where([['transaction_type', 2], ['type', 1]])->with('payment_type_details');
    }

    public static function sale_bill_datas($sales_order_ids)
    {
        return SalesOrderDetail::whereIn('id', $sales_order_ids)->get();
    }
    // get attributes customized
    public function getImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getStatusTextAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['name'] : $data[1]['name'];
        } else {
            return config('app.purchase_status')[0]['name'];
        }

        // $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
        // return $data[$this->status]['name'];
    }

    public function getStatusColorCodeAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.purchase_status')[0]['color_code'];
        }

        // $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
        // return $data[$this->status]['color_code'];
    }

    public function getPaymentStatusTextAttribute()
    {
        if ((!empty($this->payment_status) || $this->payment_status != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.payment_status'));
            return (!empty($data[$this->payment_status]) || $data[$this->payment_status] != "") ? $data[$this->payment_status]['name'] : $data[1]['name'];
        } else {
            return config('app.payment_status')[0]['name'];
        }
    }

    public function getPaymentStatusColorCodeAttribute()
    {
        if ((!empty($this->payment_status) || $this->payment_status != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.payment_status'));
            return (!empty($data[$this->payment_status]) || $data[$this->payment_status] != "") ? $data[$this->payment_status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.payment_status')[0]['color_code'];
        }
    }
}
