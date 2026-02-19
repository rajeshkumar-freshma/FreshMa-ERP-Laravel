<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class TransportTracking extends Model
{
    protected $appends = ['image_full_url'];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->creator_type = 1;
                $data->creator_admin_id = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->creator_type = 1;
                $data->creator_admin_id = Auth::user()->id;
            } else {
                $data->creator_type = 2;
                $data->creator_user_id = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->creator_type = 1;
                $data->creator_admin_id = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->creator_type = 1;
                $data->creator_admin_id = Auth::user()->id;
            } else {
                $data->creator_type = 2;
                $data->creator_user_id = 1;
            }
        });
    }

    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
    }

    /* Relationship Container */
    public function transport_type_details()
    {
        return $this->hasOne(TransportType::class, 'id', 'transport_type_id');
    }
}
