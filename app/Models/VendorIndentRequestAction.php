<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class VendorIndentRequestAction extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($data) {
            $data->action_by = static::resolveActorId();
        });
    }

    private static function resolveActorId(): int
    {
        return Auth::guard('admin')->id() ?? Auth::guard('api')->id() ?? Auth::id() ?? 1;
    }
}
