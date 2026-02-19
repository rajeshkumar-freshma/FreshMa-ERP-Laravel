<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

/* Default data store Container */
protected static function booted()
{
    static::creating(function ($data) {
        if (Auth::guard('admin')->check()) {
            $data->created_by = Auth::user()->id;
        } else if (Auth::guard('api')->check()) {
            $data->created_by = Auth::user()->id;
        } else {
            $data->created_by = 1;
        }
    });
}

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
