<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\DataTables\Purchase\ProductPurchaseCreditDataTable;
use App\DataTables\Purchase\ProductPurchaseDataTable;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpenseType;
use App\Models\PaymentType;
use App\Models\PurchaseOrder;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseIndentRequest;
use Illuminate\Http\Request;

class ProductPurchaseCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductPurchaseCreditDataTable $dataTable)
    {
        return $dataTable->render('pages.purchase.purchase_credit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
