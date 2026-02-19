<?php

namespace App\Models;

use App\Core\CommonComponent;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class Spoilage extends Model
{
    protected $appends = ['status_text', 'status_color_code'];

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

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function from_warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'from_warehouse_id');
    }

    public function from_store()
    {
        return $this->hasOne(Store::class, 'id', 'from_store_id');
    }

    public function to_warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'to_warehouse_id');
    }

    public function supplier_details()
    {
        return $this->hasOne(User::class, 'id', 'to_supplier_id');
    }

    public function product_details()
    {
        return $this->hasMany(SpoilageProductDetail::class, 'spoilage_id', 'id');
    }

    public function expense_details()
    {
        return $this->hasMany(ProductTransferExpense::class, 'spoilage_id', 'id');
    }

    public function expense_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 1], ['type', 5]]); // 4 => spoilage 1 => expense
    }

    public function transport_details()
    {
        return $this->hasOne(TransportTracking::class, 'spoilage_id', 'id')->orderBy('id', 'DESC');
    }

    public function transporttracking_documents()
    {
        return $this->hasMany(PurchaseSalesDocument::class, 'reference_id', 'id')->where([['document_type', 2], ['type', 5]]); // 4 => spoilage 2 => transfer tracking
    }
    public function getStatusTextAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['name'] : $data[1]['name'];
        } else {
            return config('app.purchase_status')[0]['name'];
        }

        // $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
        // return $data[$this->status]['name'];
    }

    public function getStatusColorCodeAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.purchase_status')[0]['color_code'];
        }

        // $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
        // return $data[$this->status]['color_code'];
    }
}
