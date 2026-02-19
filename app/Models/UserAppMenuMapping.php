<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class UserAppMenuMapping extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function admin_detail()
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function supplier_details()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }
}
