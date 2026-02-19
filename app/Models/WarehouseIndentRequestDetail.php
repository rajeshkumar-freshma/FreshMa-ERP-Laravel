<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class WarehouseIndentRequestDetail extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function warehouse_indent_request()
    {
        return $this->hasOne(WarehouseIndentRequest::class, 'id', 'warehouse_ir_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function unit_details()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
}
