<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;

class MachineData extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    public function live_sales_bills()
    {
        return $this->hasMany(LiveSalesBill::class, 'MachineName', 'id');
    }

    public function store_details()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}
