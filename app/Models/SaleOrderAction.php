<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;


class SaleOrderAction extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->action_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->action_by = Auth::user()->id;
            } else {
                $data->action_by = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->action_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->action_by = Auth::user()->id;
            } else {
                $data->action_by = 1;
            }
        });
    }
}
