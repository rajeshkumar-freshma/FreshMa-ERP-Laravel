<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\FishCuttingDetailsReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class FishCuttingDetailsReportController extends Controller
{
    public function fishCuttingDetailsData(FishCuttingDetailsReportDataTable $dataTable)
    {
        $data['products'] = Product::all();
        $data['stores'] = Store::all();
        return $dataTable->render('pages.report.fish_cutting.index',$data);
    }
}
