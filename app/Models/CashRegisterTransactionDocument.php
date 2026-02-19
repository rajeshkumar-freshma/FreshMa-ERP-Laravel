<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;

class CashRegisterTransactionDocument extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    protected $appends = ['image_full_url'];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->attached_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->attached_by = Auth::user()->id;
            } else {
                $data->attached_by = 1;
            }
        });
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'attached_by');
    }

        public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
    }

   
}
