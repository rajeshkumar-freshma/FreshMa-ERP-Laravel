<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
class LiveSalesBill extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;

    public function machine_details()
    {
        return $this->belongsTo(MachineData::Class, 'MachineName', 'id');
    }

    public function sale_bill_details()
    {
        return $this->belongsToMany(LiveSalesBillDetail::Class, 'MachineName', 'id');
    }

    public static function sale_bill_datas($billNo, $MachineName)
    {
        return LiveSalesBillDetail::whereIn('billNo', $billNo)->whereIn('MachineName', $MachineName)->get();
    }
}
