<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;

class ProductTransfer extends Model
{
    protected $appends = ['image_full_url', 'status_text', 'status_color_code'];

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

    public function from_warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'from_warehouse_id')->withTrashed();
    }

    public function from_store()
    {
        return $this->hasOne(Store::class, 'id', 'from_store_id')->withTrashed();
    }

    public function to_warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'to_warehouse_id')->withTrashed();
    }

    public function to_store()
    {
        return $this->hasOne(Store::class, 'id', 'to_store_id')->withTrashed();
    }

    public function product_details()
    {
        return $this->hasMany(ProductTransferDetail::class, 'product_transfer_id', 'id');
    }

    public function store_indent_request()
    {
        return $this->hasOne(StoreIndentRequest::class, 'id', 'store_indent_request_id')->withTrashed();
    }

    public function expense_details()
    {
        return $this->hasMany(ProductTransferExpense::class, 'product_transfer_id', 'id');
    }

    public function expense_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 1], ['type', 4]]); // 4 => transfer 1 => expense
    }

    public function transport_details()
    {
        return $this->hasMany(TransportTracking::class, 'product_transfer_id', 'id')->orderBy('id', 'DESC');
    }

    public function transporttracking_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 2], ['type', 4]]); // 4 => transfer 2 => transfer tracking
    }

    public function purchase_order_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')->where([['transaction_type', 1]])->with('payment_type_details');
    }

    // get attributes customized
    public function getImageFullUrlAttribute()
    {
        return commoncomponent::getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getStatusTextAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = commoncomponent::arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['name'] : $data[1]['name'];
        } else {
            return config('app.purchase_status')[0]['name'];
        }
    }

    public function getStatusColorCodeAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = commoncomponent::arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.purchase_status')[0]['color_code'];
        }
    }

    public function getPaymentStatusTextAttribute()
    {
        if ((!empty($this->payment_status) || $this->payment_status != "")) {
            $data = commoncomponent::arraypositionconversion(config('app.payment_status'));
            return (!empty($data[$this->payment_status]) || $data[$this->payment_status] != "") ? $data[$this->payment_status]['name'] : $data[1]['name'];
        } else {
            return config('app.payment_status')[0]['name'];
        }
    }

    public function getPaymentStatusColorCodeAttribute()
    {
        if ((!empty($this->payment_status) || $this->payment_status != "")) {
            $data = commoncomponent::arraypositionconversion(config('app.payment_status'));
            return (!empty($data[$this->payment_status]) || $data[$this->payment_status] != "") ? $data[$this->payment_status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.payment_status')[0]['color_code'];
        }
    }
}
