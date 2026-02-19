<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;

class StaffAdvanceHistory extends Model
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
    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getSignatureFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->signature, $this->signature_path);
    }
    public function payment_type()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }
}
