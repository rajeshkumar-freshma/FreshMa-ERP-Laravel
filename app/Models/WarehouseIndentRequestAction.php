<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class WarehouseIndentRequestAction extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->actioned_user_type = 1;
                $data->action_by_admin_id = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->actioned_user_type = 1;
                $data->action_by_admin_id = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->actioned_user_type = 2;
                $data->action_by_admin_id = Auth::user()->id;
            } else {
                $data->actioned_user_type = 2;
                $data->action_by_admin_id = 1;
            }
        });
    }
}
