<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceDetails extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $guarded = [];
    public function staff()
{
    return $this->hasOne(Staff::class, 'staff_id', 'id');
}
}
