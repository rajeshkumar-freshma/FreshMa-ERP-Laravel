<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;

class SalesOrderReturn extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    protected $appends = ['image_full_url', 'status_text', 'status_color_code', 'payment_status_text', 'payment_status_color_code', 'return_from_text'];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->return_authorised_person = Auth::user()->id;
            } elseif (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->return_authorised_person = Auth::user()->id;
            } elseif (Auth::guard('supplier')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->return_authorised_person = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->updated_by = 1;
                $data->return_authorised_person = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('supplier')->check()) {
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

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function from_store()
    {
        return $this->hasOne(Store::class, 'id', 'from_store_id');
    }

    public function from_vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'from_vendor_id');
    }

    public function to_warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'to_warehouse_id');
    }

    public function sales_order_details()
    {
        return $this->hasOne(SalesOrder::class, 'id', 'sales_order_id');
    }

    public function order_details()
    {
        return $this->hasMany(SalesOrderReturnDetail::class, 'sales_order_return_id', 'id');
    }

    public function expense_details()
    {
        return $this->hasMany(SalesOrderReturnExpense::class, 'sales_order_return_id', 'id');
    }

    public function transport_details()
    {
        return $this->hasMany(TransportTracking::class, 'sales_order_return_id', 'id')->orderBy('id', 'DESC');
    }

    public function expense_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 1], ['type', 3]]); //document_type = 1 => expense, 3 => store return
    }

    public function transporttracking_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 2], ['type', 3]]); //document_type = 2 => tracking, 3 => store return
    }

    public function sales_return_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')->where([['transaction_type', 3]])->with('payment_type_details');
    }
    public function return_order_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')
            ->where([['transaction_type', 3]])
            ->with('payment_type_details', 'payment_transaction_documents');
    }

    public function return_order_paid_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')
            ->where([['transaction_type', 3], ['type', 1]])
            ->with('payment_type_details', 'payment_transaction_documents');
    }

    public function return_order_refund_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')
            ->where([['transaction_type', 3], ['type', 2]])
            ->with('payment_type_details', 'payment_transaction_documents');
    }


    // get attributes customized
    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getStatusTextAttribute()
    {
        if (!empty($this->status) && $this->status != '') {
            $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
            return !empty($data[$this->status]) && $data[$this->status] != '' ? $data[$this->status]['name'] : $data[1]['name'];
        } else {
            return config('app.purchase_status')[0]['name'];
        }

        // $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
        // return $data[$this->status]['name'];
    }

    public function getStatusColorCodeAttribute()
    {
        if (!empty($this->status) && $this->status != '') {
            $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
            return !empty($data[$this->status]) && $data[$this->status] != '' ? $data[$this->status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.purchase_status')[0]['color_code'];
        }

        // $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
        // return $data[$this->status]['color_code'];
    }


    public function getPaymentStatusTextAttribute()
    {
        if (!empty($this->payment_status) || $this->payment_status != '') {
            $data = commoncomponent()->arraypositionconversion(config('app.payment_status'));
            return !empty($data[$this->payment_status]) || $data[$this->payment_status] != '' ? $data[$this->payment_status]['name'] : $data[1]['name'];
        } else {
            return config('app.payment_status')[0]['name'];
        }
    }

    public function getPaymentStatusColorCodeAttribute()
    {
        if (!empty($this->payment_status) || $this->payment_status != '') {
            $data = commoncomponent()->arraypositionconversion(config('app.payment_status'));
            return !empty($data[$this->payment_status]) || $data[$this->payment_status] != '' ? $data[$this->payment_status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.payment_status')[0]['color_code'];
        }
    }
    public function getReturnFromTextAttribute()
    {
        if ($this->return_from == 1) {
            return "Store";
        } else {
            return "Customer";
        }
    }
}
