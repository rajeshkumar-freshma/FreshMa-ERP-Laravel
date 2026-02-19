<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Traits\SpatieLogsActivity;

class BaseModel extends Model
{
    use SpatieLogsActivity;
}
