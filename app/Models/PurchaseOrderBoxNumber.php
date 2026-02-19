<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class PurchaseOrderBoxNumber extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }

    public function purchase_orders()
    {
        return $this->hasOne(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
}
