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
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
        });

        static::updating(function ($data) {
            $data->updated_by = Auth::user()->id;
        });
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
