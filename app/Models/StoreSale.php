<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class StoreSale extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->updated_by = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->updated_by = Auth::user()->id;
            } else {
                $data->updated_by = 1;
            }
        });
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function transport_details()
    {
        return $this->hasMany(TransportTracking::class, 'store_sale_id', 'id');
    }

    public function expense_details()
    {
        return $this->hasMany(SalesExpense::class, 'store_sale_id', 'id');
    }

    public function product_details()
    {
        return $this->hasMany(StoreSaleProductDetail::class, 'store_sale_id', 'id');
    }
}
