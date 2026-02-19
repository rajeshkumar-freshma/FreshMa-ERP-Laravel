<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\SupplierScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Core\CommonComponent;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $table = 'users';
    protected $guard_name = 'web';

    protected $appends = ['user_type_text', 'name'];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierScope);
    }

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id')->where('admin_type', 2); // 2=> User table
    }

    public function purchase_order()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id', 'id');
    }

    public function salary_details()
    {
        return $this->hasOne(SalaryDetail::class, 'user_id', 'id')->where('admin_type', 2); // 2=> User table
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

    public function getUserTypeTextAttribute()
    {
        $data = CommonComponent::arraypositionconversion(config('app.user_of_user_type'));

        return $data[$this->user_type]['name'];
    }
}
