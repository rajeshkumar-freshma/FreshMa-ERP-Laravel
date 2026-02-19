<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class DenominationType extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $guarded = [];
    protected $fillable = [
        'type',
        'value',
        'denomination_code',
        'description',
    ];
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $latest = self::orderBy('id', 'desc')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $model->denomination_code = 'DN' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }
}
