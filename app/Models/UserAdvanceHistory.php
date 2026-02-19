<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class UserAdvanceHistory extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function advance_transactions()
    {
        return $this->hasOne(PaymentTransaction::class, 'reference_id', 'id')->where([['transaction_type', 6]])->with('payment_type_details');
    }
}
