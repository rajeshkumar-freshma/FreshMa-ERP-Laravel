<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\HRM\EmployeeReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\StaffStoreMapping;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function employeeReport(EmployeeReportDataTable $dataTable)
    {

        // $store = Store::with('staff', function ($query) {
        //     $query->plcuk('id')->get();
        // });
        // $id = [];
        // foreach ($store as $item) {
        //     return $id[] = $item->id;
        // }
        // return $id;
        return $dataTable->render('pages.report.employee_report.index');
    }


    public function view($values)
    {
        // $data['salesOrder'] = Store::findOrFail($id)
        //     ->select(
        //         'sales_orders.id',
        //         'sales_orders.store_id',
        //         'sales_orders.total_amount',
        //         'sales_orders.delivered_date',
        //         DB::raw('count(sales_orders.id) as total_count'),
        //     )
        //     ->get();
        // Ensure $values is converted to an array or an object if it's a string
        if (is_string($values)) {
            $values = json_decode($values, true); // Convert JSON string to associative array
        }

        $satffs = StaffStoreMapping::whereIn('staff_id', $values)->with('department', 'designation','admin')->get();

        return view('pages.report.employee_report.view', ['staffDetails' => $satffs]);

        // Now $values should be an array or an object
        // Pass $values to the view
        return view('pages.report.employee_report.view', ['staffDetails' => $satffs]);
    }
}
