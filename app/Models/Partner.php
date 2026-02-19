<?php

namespace App\Models;

use App\Scopes\PartnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class Partner extends Model
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, SpatieLogsActivity;

    protected $table = 'admins';
    protected $guard_name = 'admin';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PartnerScope);
    }

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'admin_id', 'id')->where('admin_type', 1); // 1=> Admin table
    }

    public function partnership_detail()
    {
        return $this->hasMany(PartnershipDetail::class, 'partner_id', 'id'); // 1=> Admin table
    }

    public function admin_store_mapping()
    {
        return $this->hasMany(AdminStoreMapping::class, 'admin_id', 'id'); // 1=> Admin table
    }
    public function admin_warehouse_mapping()
    {
        return $this->hasMany(AdminWarehouseMapping::class, 'admin_id', 'id'); // 1=> Admin table
    }
}
