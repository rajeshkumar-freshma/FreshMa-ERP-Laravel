<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class TaxRate extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
        });

        static::updating(function ($data) {
            $data->updated_by = Auth::user()->id;
        });
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    /* Scope Container */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
