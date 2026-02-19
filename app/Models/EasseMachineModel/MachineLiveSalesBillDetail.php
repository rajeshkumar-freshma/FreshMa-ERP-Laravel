<?php

namespace App\Models\EasseMachineModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLiveSalesBillDetail extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_ease';
    protected $table = 'billSalesDetails_Duplicate';
}
