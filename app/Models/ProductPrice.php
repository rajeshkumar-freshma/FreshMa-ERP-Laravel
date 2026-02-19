<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function product_details()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function store_details()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
    public function product_price_details()
    {
        return $this->hasMany(ProductPriceDetails::class);
    }
}
