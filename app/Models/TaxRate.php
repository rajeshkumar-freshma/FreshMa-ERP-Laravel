<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class TaxRate extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            $data->created_by = static::resolveActorId();
            $data->updated_by = static::resolveActorId();
        });

        static::updating(function ($data) {
            $data->updated_by = static::resolveActorId();
        });
    }

    private static function resolveActorId(): int
    {
        return Auth::guard('admin')->id() ?? Auth::guard('api')->id() ?? Auth::id() ?? 1;
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    /* Scope Container */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
