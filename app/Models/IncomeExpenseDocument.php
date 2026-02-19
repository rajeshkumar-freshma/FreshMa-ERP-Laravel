<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class IncomeExpenseDocument extends Model
{
    protected $appends = ['image_full_url'];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

        // get attributes customized
        public function getImageFullUrlAttribute()
        {
            return commoncomponent()->getImageFullUrlPath($this->file, $this->file_path);
        }
}
