<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class CashRegisterTransaction extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $guarded = [];
    /* Relationship Container */
    public function cash_register_details()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    public function payment_type_details()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }

    public function cash_register_transaction_documents()
    {
        return $this->hasMany(CashRegisterTransactionDocument::class, 'crt_id', 'id');
    }

    public function denominations()
    {
        return $this->hasMany(Denomination::class, 'cash_register_transaction_id',  'id');
    }
}
