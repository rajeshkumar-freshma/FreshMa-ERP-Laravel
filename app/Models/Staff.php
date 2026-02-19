<?php

namespace App\Models;

use App\Scopes\StaffScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'admins';
    protected $guard_name = 'admin';

    protected $appends = ['user_type_text', 'name'];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StaffScope);
    }

    /**
     * Get a fullname combination of first_name and last_name
     *
     * @return string
     */
    /* Scope Container */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'admin_id', 'id')->where('admin_type', 1); // 1=> Admin table
    }

    public function staff_store_mapping()
    {
        return $this->hasMany(StaffStoreMapping::class, 'staff_id', 'id'); // 1=> Admin table
    }


    // Attribute Details
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getUserTypeTextAttribute()
    {
        if ((!empty($this->user_type) || $this->user_type != "")) {
            $data = commoncomponent()->arraypositionconversion(config('app.admin_of_user_type'));
            return (!empty($data[$this->user_type]) || $data[$this->user_type] != "") ? $data[$this->user_type]['name'] : $data[1]['name'];
        } else {
            return config('app.admin_of_user_type')[0]['name'];
        }
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'supplier_id');
    }
}
