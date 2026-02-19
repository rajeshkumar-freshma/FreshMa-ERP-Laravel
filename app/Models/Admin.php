<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Auth;
use App\Core\Traits\SpatieLogsActivity;
use Laravel\Passport\HasApiTokens;
use App\Core\CommonComponent;
class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SpatieLogsActivity;

    protected $guard = 'admin';
    protected $guard_name = 'admin';

    protected $guarded = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

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
        return null;
        // if ($this->info) {
        //     return asset($this->info->avatar_url);
        // }

        // return asset(theme()->getMediaUrlPath().'avatars/blank.png');
    }

    /**
     * User relation to info model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

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

    public function getUserTypeTextAttribute()
    {
        if ((!empty($this->user_type) && $this->user_type != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.admin_of_user_type'));
            return (!empty($data[$this->user_type]) && $data[$this->user_type] != "") ? $data[$this->user_type]['name'] : $data[1]['name'];
        } else {
            return config('app.admin_of_user_type')[0]['name'];
        }
    }

    public function advances()
    {
        return $this->hasOne(UserAdvance::class, 'id', 'user_id')->where('status', 1)->where('type', 1);
    }
}
