<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class ProductTransferDetail extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Relationship Container */
    public function unit_details()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function product_transfer()
    {
        return $this->hasOne(ProductTransfer::class, 'id', 'product_transfer_id');
    }
}
