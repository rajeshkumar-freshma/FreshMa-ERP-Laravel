<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenominationDateAmount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cash_paid_to_office()
    {
        return $this->hasMany(CashPaidToOffice::class, 'cash_paid_id', 'id');
    }
}
