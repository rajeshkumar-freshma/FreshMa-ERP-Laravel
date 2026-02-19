<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;

class ProductBulkTransfer extends Model
{
    protected $appends = ['image_full_url', 'status_text', 'status_color_code'];

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
        return $this->hasOne(Warehouse::class, 'id', 'from_warehouse_id')->withTrashed();
    }

    public function product_details()
    {
        return $this->hasMany(ProductBulkTransferDetail::class, 'product_bulk_transfer_id', 'id');
    }

    // get attributes customized
    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getStatusTextAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['name'] : $data[1]['name'];
        } else {
            return config('app.purchase_status')[0]['name'];
        }
    }

    public function getStatusColorCodeAttribute()
    {
        if ((!empty($this->status) && $this->status != "")) {
            $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));
            return (!empty($data[$this->status]) && $data[$this->status] != "") ? $data[$this->status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.purchase_status')[0]['color_code'];
        }
    }
}
