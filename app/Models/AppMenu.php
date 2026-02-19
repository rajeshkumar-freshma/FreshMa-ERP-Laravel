<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppMenu extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];
}
