<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class LoanRepayment extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $guarded = [];

    public function loans()
    {
        return $this->belongsTo(Loan::class,'loan_id','id');
    }
}
