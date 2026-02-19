<?php

namespace App\Models;

use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\VendorScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $table = 'users';

    protected $appends = ['user_type_text', 'name'];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope);
    }

    /**
     * Get a fullname combination of first_name and last_name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /* Scope Container */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id')->where('admin_type', 2); // 2=> User table
    }

    public function vendor_detail()
    {
        return $this->hasOne(VendorDetail::class, 'vendor_id', 'id')->orderBy('id', 'DESC');
    }

    public function getUserTypeTextAttribute()
    {
        $data = CommonComponent::arraypositionconversion(config('app.user_of_user_type'));

        return $data[$this->user_type]['name'];
    }

    public function getImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->image, $this->image_path);
    }
}
