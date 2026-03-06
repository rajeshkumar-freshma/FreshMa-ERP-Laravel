<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminStoreMapping extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            $data->assigned_by = static::resolveActorId();
        });
    }

    private static function resolveActorId(): int
    {
        return Auth::guard('admin')->id() ?? Auth::guard('api')->id() ?? Auth::id() ?? 1;
    }
}
