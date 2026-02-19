<?php

namespace App\Models\EasseMachineModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLiveSalesBill extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_ease';
    protected $table = 'billSales_Duplicate';
}
