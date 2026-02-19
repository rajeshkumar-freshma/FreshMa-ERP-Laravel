<?php

namespace App\Http\Controllers\Admin\Sales;

use App\DataTables\Sales\SalesCreditDataTable;
use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\TaxRate;
use App\Models\SalesOrder;
use App\Models\MachineData;
use App\Models\SalesOrderDetail;
use App\Models\Product;
use App\Models\SaleOrderAction;
use App\Models\SalesExpense;
use App\Models\TransportTracking;
use App\Models\TransportType;
use App\Models\IncomeExpenseType;
use App\Models\VendorIndentRequest;
use App\Models\VendorIndentRequestDetail;
use App\Models\VendorIndentRequestAction;
use App\Http\Requests\IndentRequest\VendorIndentFormRequest;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SalesCreditDataTable $dataTable)
    {
        $data['store'] = Store::get();
        return $dataTable->render('pages.sales.sales_credit.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
