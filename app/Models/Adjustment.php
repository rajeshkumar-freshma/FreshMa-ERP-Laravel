<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class Adjustment extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $appends = ['image_full_url'];

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            $actorId = static::resolveActorId();
            $data->created_by = $actorId;
            $data->updated_by = $actorId;
        });

        static::updating(function ($data) {
            $data->updated_by = static::resolveActorId();
        });
    }

    private static function resolveActorId(): int
    {
        return Auth::guard('admin')->id() ?? Auth::guard('api')->id() ?? Auth::id() ?? 1;
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function adjustment_details()
    {
        return $this->hasMany(AdjustmentDetail::class, 'adjustment_id', 'id');
    }
    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->image, $this->image_path);
    }
}
