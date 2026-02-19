<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;
use App\Core\CommonComponent;


class PaymentType extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $appends = ['image_full_url'];

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


    public function cash_register_transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class, 'payment_type_id', 'id');
    }

    /* Scope Container */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->file, $this->file_path);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'payment_type_id');
    }

}
