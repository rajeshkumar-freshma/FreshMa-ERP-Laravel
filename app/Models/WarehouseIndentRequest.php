<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class WarehouseIndentRequest extends Model
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
                $data->approved_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->approved_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('supplier')->check()) {
                $data->created_by = Auth::user()->id;
                $data->approved_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->approved_by = 1;
                $data->updated_by = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('supplier')->check()) {
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

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function warehouse_indent_product_details()
    {
        return $this->hasMany(WarehouseIndentRequestDetail::class, 'warehouse_ir_id', 'id');
    }

    // get attributes customized
    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getStatusTextAttribute()
    {
        $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));

        return $data[$this->status]['name'];
    }


    public function getStatusColorCodeAttribute()
    {
        $data = commoncomponent()->arraypositionconversion(config('app.purchase_status'));

        return $data[$this->status]['color_code'];
    }
}
