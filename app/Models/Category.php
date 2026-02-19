<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class Category extends Model
{
    protected $appends = ['image_full_url'];

    protected $guarded = [];
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
        });

        static::updating(function ($data) {
            $data->updated_by = Auth::user()->id;
        });
    }

    /* Relationship Container */
    public function getCategory()
    {
        return $this->hasMany($this, 'parent_id')->select(['id', 'name', 'status', 'parent_id']);
    }

    public function getChildrenCategory()
    {
        return $this->getCategory()->with('getChildrenCategory');
    }

    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function getParent()
    {
        return $this->hasOne($this, 'id', 'parent_id');
    }

    public function getParentNameAttribute()
    {
        return $this->parent_id ? @$this->getParent->name : '-';
    }

    /* Scope Container */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImageFullUrlAttribute()
    {
        return commoncomponent()->getImageFullUrlPath($this->image, $this->image_path);
    }
}
