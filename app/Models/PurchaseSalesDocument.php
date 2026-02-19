<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class PurchaseSalesDocument extends Model
{
    protected $appends = ['image_full_url'];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

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

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->attached_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->attached_by = Auth::user()->id;
            } else {
                $data->attached_by = 1;
            }
        });
    }

    public function getImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->file, $this->file_path);
    }
}
