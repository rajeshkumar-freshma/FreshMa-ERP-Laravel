<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class StoreIndentRequestDetail extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function store_indent_request()
    {
        return $this->hasOne(StoreIndentRequest::class, 'id', 'store_indent_request_id');
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
