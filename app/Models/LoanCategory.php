<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class LoanCategory extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    // protected $casts = [
    //     'amount' => 'decimal:2', // Adjust the precision and scale as needed
    // ];

    // protected $casts = [
    //     'charges' => 'array',
    // ];

    // public function loan_charges()
    // {
    //     return $this->belongsToMany(LoanCharge::class,'charges','id');
    // }
}
