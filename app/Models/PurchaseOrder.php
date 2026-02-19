<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;
use Illuminate\Support\Facades\Log;

class PurchaseOrder extends Model
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
                $data->approved_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->approved_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->approved_by = 1;
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

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function user_details()
    {
        return $this->hasOne(User::class, 'id', 'supplier_id');
    }

    public function user_advances()
    {
        return $this->hasMany(UserAdvance::class, 'user_id', 'supplier_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function warehouse_request()
    {
        return $this->hasOne(WarehouseIndentRequest::class, 'id', 'warehouse_ir_id');
    }

    public function purchase_order_product_details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id', 'id');
    }

    public function expense_details()
    {
        return $this->hasMany(PurchaseOrderExpense::class, 'purchase_order_id', 'id');
    }

    public function transport_details()
    {
        return $this->hasMany(TransportTracking::class, 'purchase_order_id', 'id');
    }

    public function box_number()
    {
        return $this->hasMany(PurchaseOrderBoxNumber::class, 'id', 'purchase_order_id');
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id');
    }
    public function paymentType()
    {
        return $this->hasMany(PaymentType::class);
    }

    public function expense_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 1], ['type', 1]]);
    }

    public function transporttracking_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 2], ['type', 1]]);
    }

    public function purchase_order_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')->where([['transaction_type', 1],['type', 2]])->with('payment_type_details')->whereNull('payment_transactions.deleted_at');
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
