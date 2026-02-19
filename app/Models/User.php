<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Core\CommonComponent;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use SpatieLogsActivity;
    use HasRoles;

    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'api_token',
        'password',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['name', 'user_type_text'];

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
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

    /**
     * Prepare proper error handling for url attribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->user_info) {
            return asset($this->user_info->image);
        }

        return asset(theme()->getMediaUrlPath().'avatars/blank.png');
    }

    /**
     * User relation to info model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_info()
    {
        return $this->hasOne(UserInfo::class);
    }

    public function scopeVendor($q)
    {
        return $q->where('user_type', 1);
    }

    public function scopeSupplier($q)
    {
        return $q->where('user_type', 2);
    }

    public function scopeCustomer($q)
    {
        return $q->where('user_type', 3);
    }

    public function getUserTypeTextAttribute()
    {
        if ((!empty($this->user_type) && $this->user_type != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.user_of_user_type'));
            return (!empty($data[$this->user_type]) && $data[$this->user_type] != "") ? $data[$this->user_type]['name'] : $data[1]['name'];
        } else {
            return config('app.user_of_user_type')[0]['name'];
        }
    }

    public function isSupplier()
    {
        return $this->user_type === 2;
    }
    public function user_stores()
    {
        if (Auth::user()->user_type == 1) { // Admin
            return $storeIds = Store::where('status', 1)->pluck('id')->toArray();
        } else if (Auth::user()->user_type == 2) { // Manager
            return $this->hasMany(AdminStoreMapping::class, 'admin_id', 'id')->where('status', 1)->pluck('store_id')->toArray();
        } else if (Auth::user()->user_type == 3) { // Partner
            return $this->hasMany(PartnershipDetail::class, 'partner_id', 'id')->where('status', 1)->pluck('store_id')->toArray();
        } else {
            return [];
        }
    }
    public function user_warehouse()
    {
        if (Auth::user()->user_type == 1) { // Admin
            return $storeIds = Warehouse::where('status', 1)->pluck('id')->toArray();
        } else if (Auth::user()->user_type == 2) { // Manager
            return $this->hasMany(AdminWarehouseMapping::class, 'admin_id', 'id')->where('status', 1)->pluck('warehouse_id')->toArray();
        } else {
            return [];
        }
    }

    public function user_store_data($admin)
    {
        if ($admin->user_type == 1) { // Admin
            return $storeIds = Store::where('status', 1)->pluck('id')->toArray();
        } else if ($admin->user_type == 2) { // Manager
            return $this->hasMany(AdminStoreMapping::class, 'admin_id', 'id')->where('status', 1)->pluck('store_id')->toArray();
        } else if ($admin->user_type == 3) { // Partner
            return $this->hasMany(PartnershipDetail::class, 'partner_id', 'id')->where('status', 1)->pluck('store_id')->toArray();
        } else {
            return [];
        }
    }

    public function user_warehouse_data($admin)
    {
        if ($admin->user_type == 1) { // Admin
            return $storeIds = Warehouse::where('status', 1)->pluck('id')->toArray();
        } else if ($admin->user_type == 2) { // Manager
            return $this->hasMany(AdminWarehouseMapping::class, 'admin_id', 'id')->where('status', 1)->pluck('warehouse_id')->toArray();
        } else {
            return [];
        }
    }
}
