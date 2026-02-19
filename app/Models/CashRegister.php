<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Auth;

class CashRegister extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            $data->created_by = Auth::user()->id;
        });
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function cash_register_transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class, 'cash_register_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function verified()
    {
        return $this->hasOne(Admin::class, 'id', 'verified_by');
    }
    public function denomination_date_amounts()
    {
        return $this->hasMany(DenominationDateAmount::class, 'cash_register_id', 'id');
    }

    public function denominations()
    {
        return $this->hasMany(Denomination::class, 'cash_register_id', 'id');
    }
}
