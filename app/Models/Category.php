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
            $actorId = static::resolveActorId();
            $data->created_by = $actorId;
            $data->updated_by = $actorId;
        });

        static::updating(function ($data) {
            $data->updated_by = static::resolveActorId();
        });
    }

    private static function resolveActorId(): int
    {
        return Auth::guard('admin')->id() ?? Auth::guard('api')->id() ?? Auth::id() ?? 1;
    }

    /* Relationship Container */
    public function getCategory()
    {
        return $this->hasMany(Category::class, 'parent_id')->select(['id', 'name', 'status', 'parent_id']);
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
        return $this->hasOne(Category::class, 'id', 'parent_id');
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
