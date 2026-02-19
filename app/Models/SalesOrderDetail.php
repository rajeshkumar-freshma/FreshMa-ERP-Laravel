<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class SalesOrderDetail extends Model
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

    public function sales_order()
    {
        return $this->hasOne(SalesOrder::class, 'id', 'sales_order_id');
    }
    public function tax_rate()
    {
        return $this->belongsTo(TaxRate::class, 'tax_id');
    }
}
