<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->created_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
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

    public function payment_type_details()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id')->select('id', 'payment_type', 'slug', 'status');
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'reference_id', 'id');
    }

    public function new_purchase_order()
    {
        // return $this->hasMany(PurchaseOrder::class, 'id', 'reference_id')->with('purchase_order_transactions');
        return $this->hasMany(PurchaseOrder::class, 'id', 'reference_id')->with('purchase_order_transactions');
    }

    public function sales_order()
    {
        return $this->belongsTo(SalesOrder::class, 'reference_id', 'id');
    }

    public function sales_return()
    {
        return $this->belongsTo(SalesOrderReturn::class, 'reference_id', 'id');
    }

    public function payment_transaction_documents()
    {
        return $this->hasMany(PaymentTransactionDocument::class, 'reference_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(Staff::class, 'id', 'created_by');
    }

    public function payment_types()
    {
        return $this->hasOne(PaymentType::class, 'payment_type_id', 'id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

}
