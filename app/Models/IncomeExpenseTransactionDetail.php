<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class IncomeExpenseTransactionDetail extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function income_expense_type()
    {
        return $this->hasOne(IncomeExpenseType::class, 'id', 'ie_type_id')->withTrashed();
    }
}
