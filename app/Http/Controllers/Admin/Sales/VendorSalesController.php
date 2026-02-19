<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Sales\VendorSaleDataTable;
use App\Models\Vendor;
use App\Models\Unit;
use App\Models\TaxRate;
use App\Models\VendorSale;
use App\Models\VendorSaleProductDetail;
use App\Models\Product;
use App\Models\VendorSalesAction;
use App\Models\SalesExpense;
use App\Models\TransportTracking;
use App\Models\TransportType;
use App\Models\IncomeExpenseType;
use App\Models\VendorIndentRequest;
use App\Models\VendorIndentRequestDetail;
use App\Models\VendorIndentRequestAction;
use App\Http\Requests\IndentRequest\VendorIndentFormRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class VendorSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VendorSaleDataTable $dataTable)
    {
        return $dataTable->render('pages.sales.vendor_sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['vendor_indent_requests'] = VendorIndentRequest::select('id', 'vendor_id', 'request_code')->get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()
            ->where('type', 2)
            ->get();
        return view('pages.sales.vendor_sales.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = commoncomponent()->s3BucketFileUpload($request->file, 'vendor_sales');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request = new VendorSale();
        $indent_request->vir_id = $request->vendor_indent_request_id;
        $indent_request->vendor_id = $request->vendor_id;
        $indent_request->vendor_sales_number = $request->vendor_sales_number;
        $indent_request->delivered_date = $request->delivered_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_given_quantity = $request->total_given_quantity;
        $indent_request->sub_total = $request->sub_total_amount;
        $indent_request->total_expense_amount = $request->total_expense_amount;
        $indent_request->total_commission_amount = $request->total_commission_amount;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $indent_request_detail = new VendorSaleProductDetail();
                $indent_request_detail->vendor_sale_id = $indent_request->id;
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->is_inc_exp_billable = isset($products['is_inc_exp_billable'][$key]) ? $products['is_inc_exp_billable'][$key] : 0;
                $indent_request_detail->unit_id = $products['unit_id'][$key];
                $indent_request_detail->request_quantity = $products['quantity'][$key];
                $indent_request_detail->given_quantity = $products['given_quantity'][$key];
                $indent_request_detail->amount = $products['amount'][$key];
                $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                $indent_request_detail->tax_id = $products['tax_id'][$key];
                $indent_request_detail->tax_value = $products['tax_value'][$key];
                $indent_request_detail->discount_type = $products['discount_type'][$key];
                $indent_request_detail->discount_amount = $products['discount_amount'][$key] != null ? $products['discount_amount'][$key] : 0;
                $indent_request_detail->discount_percentage = $products['discount_percentage'][$key] != null ? $products['discount_percentage'][$key] : 0;
                $indent_request_detail->sub_total = $products['sub_total'][$key];
                $indent_request_detail->commission_percentage = $products['commission_percentage'][$key];
                $indent_request_detail->commission_amount = $products['commission_amount'][$key];
                $indent_request_detail->expense_amount = $products['inc_exp_amount'][$key];
                $indent_request_detail->total = $products['total'][$key];
                $indent_request_detail->save();
            }
        }

        $request_action = new VendorSalesAction();
        $request_action->vendor_sale_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        // Expense Details store
        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                $purchase_expense = new SalesExpense();
                $purchase_expense->vendor_sale_id = $indent_request->id;
                $purchase_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $purchase_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $purchase_expense->is_billable = @$expense['is_billable'][$expense_key];
                $purchase_expense->save();
            }
        }
        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                $imagePath = null;
                $imageUrl = null;
                if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                    $imageData = commoncomponent()->s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];
                }

                $transport_trackings = new TransportTracking();
                $transport_trackings->vendor_sale_id = $indent_request->id;
                $transport_trackings->transport_type_id = $transport_tracking['transport_type_id'][$track_key];
                $transport_trackings->transport_name = $transport_tracking['transport_name'][$track_key];
                $transport_trackings->transport_number = $transport_tracking['transport_number'][$track_key];
                $transport_trackings->departure_datetime = $transport_tracking['departure_datetime'][$track_key];
                $transport_trackings->arriving_datetime = $transport_tracking['arriving_datetime'][$track_key];
                $transport_trackings->from_location = $transport_tracking['from_location'][$track_key];
                $transport_trackings->to_location = $transport_tracking['to_location'][$track_key];
                if ($imageUrl != null) {
                    $transport_trackings->file = $imageUrl;
                    $transport_trackings->file_path = $imagePath;
                }
                $transport_trackings->save();
            }
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.customer-sales.index')
                ->with('success', 'Vendor Sale Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Vendor Sale Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Vendor Sale Stored Fail');
        // }
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
        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['vendor_indent_requests'] = VendorIndentRequest::select('id', 'vendor_id', 'request_code')->get();
        $data['vendor_sale'] = VendorSale::findOrfail($id);
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()
            ->where('type', 2)
            ->get();
        return view('pages.sales.vendor_sales.edit', $data);
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
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = commoncomponent()->s3BucketFileUpload($request->file, 'vendor_sales');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request = VendorSale::findOrfail($id);
        $indent_request->vir_id = $request->vendor_indent_request_id;
        $indent_request->vendor_id = $request->vendor_id;
        $indent_request->vendor_sales_number = $request->vendor_sales_number;
        $indent_request->delivered_date = $request->delivered_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_given_quantity = $request->total_given_quantity;
        $indent_request->sub_total = $request->sub_total_amount;
        $indent_request->total_expense_amount = $request->total_expense_amount;
        $indent_request->total_commission_amount = $request->total_commission_amount;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        $request_old_ids = [];
        foreach ($request->products['product_id'] as $store_key => $value) {
            if ($request->products['id'][$store_key] != null) {
                $request_old_ids[] = $request->products['id'][$store_key];
            }
        }

        $exists_indent_product = VendorSaleProductDetail::where('vendor_sale_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    VendorSaleProductDetail::where('id', $value->id)->delete();
                }
            }
        }

        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $indent_request_detail = VendorSaleProductDetail::findOrFail($products['id'][$key]);
                    }
                    // else {
                    //     $indent_request_detail = new VendorSaleProductDetail();
                    //     $indent_request_detail->vendor_sale_id = $indent_request->id;
                    // }
                } else {
                    $indent_request_detail = new VendorSaleProductDetail();
                    $indent_request_detail->vendor_sale_id = $indent_request->id;
                }
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->is_inc_exp_billable = isset($products['is_inc_exp_billable'][$key]) ? $products['is_inc_exp_billable'][$key] : 0;
                $indent_request_detail->unit_id = $products['unit_id'][$key];
                $indent_request_detail->request_quantity = $products['quantity'][$key];
                $indent_request_detail->given_quantity = $products['given_quantity'][$key];
                $indent_request_detail->amount = $products['amount'][$key];
                $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                $indent_request_detail->tax_id = $products['tax_id'][$key];
                $indent_request_detail->tax_value = $products['tax_value'][$key];
                $indent_request_detail->discount_type = $products['discount_type'][$key];
                $indent_request_detail->discount_amount = $products['discount_amount'][$key] != null ? $products['discount_amount'][$key] : 0;
                $indent_request_detail->discount_percentage = $products['discount_percentage'][$key] != null ? $products['discount_percentage'][$key] : 0;
                $indent_request_detail->sub_total = $products['sub_total'][$key];
                $indent_request_detail->commission_percentage = $products['commission_percentage'][$key];
                $indent_request_detail->commission_amount = $products['commission_amount'][$key];
                $indent_request_detail->expense_amount = $products['inc_exp_amount'][$key];
                $indent_request_detail->total = $products['total'][$key];
                $indent_request_detail->is_inc_exp_billable = isset($request->is_inc_exp_billable_for_all) ? $request->is_inc_exp_billable_for_all : 0;
                $indent_request_detail->save();
            }
        }

        $request_action = new VendorSalesAction();
        $request_action->vendor_sale_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        // Expense Details store
        $request_old_expense_ids = [];
        foreach ($request->expense['expense_type_id'] as $exp_key => $value) {
            if ($request->expense['expense_id'][$exp_key] != null) {
                $request_old_expense_ids[] = $request->expense['expense_id'][$exp_key];
            }
        }

        $s_e_details = SalesExpense::where('vendor_sale_id', $id)->get();
        if (count($s_e_details) > 0) {
            foreach ($s_e_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_expense_ids)) {
                    SalesExpense::where('id', $value->id)->delete();
                }
            }
        }

        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                if (isset($expense['expense_id'][$expense_key]) && $expense['expense_id'][$expense_key] != null) {
                    if (in_array($expense['expense_id'][$expense_key], $s_e_details->pluck('id')->toArray())) {
                        $sales_expense = SalesExpense::findOrFail($expense['expense_id'][$expense_key]);
                    }
                    // else {
                    //     $sales_expense = new SalesExpense();
                    //     $sales_expense->vendor_sale_id = $indent_request->id;
                    // }
                } else {
                    $sales_expense = new SalesExpense();
                    $sales_expense->vendor_sale_id = $indent_request->id;
                }
                $sales_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $sales_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $sales_expense->is_billable = @$expense['is_billable'][$expense_key] != null ? @$expense['is_billable'][$expense_key] : 0;
                $sales_expense->save();
            }
        }

        $ptt_details = TransportTracking::where('vendor_sale_id', $id)->get();
        $request_old_ptt_ids = [];
        if (isset($request->transport_tracking['transport_type_id']) && count($request->transport_tracking['transport_type_id']) > 0) {
            foreach ($request->transport_tracking['transport_type_id'] as $exp_key => $value) {
                if ($request->transport_tracking['transport_tracking_id'][$exp_key] != null) {
                    $request_old_ptt_ids[] = $request->transport_tracking['transport_tracking_id'][$exp_key];
                }
            }
        }

        if (count($ptt_details) > 0) {
            foreach ($ptt_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ptt_ids)) {
                    TransportTracking::where('id', $value->id)->delete();
                }
            }
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                if (isset($transport_tracking['transport_type_id'][$track_key]) && $transport_tracking['transport_type_id'][$track_key] != null && $transport_tracking['transport_type_id'][$track_key] != "null") {
                    $imagePath = null;
                    $imageUrl = null;
                    if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                        $imageData = commoncomponent()->s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_transport');
                        $imagePath = $imageData['filePath'];
                        // $imageUrl = $imageData['fileName'];
                        $imageUrl = $imageData['imageURL'];
                    }

                    if (isset($transport_tracking['transport_tracking_id'][$track_key]) && $transport_tracking['transport_tracking_id'][$track_key] != null) {
                        if (in_array($transport_tracking['transport_tracking_id'][$track_key], $ptt_details->pluck('id')->toArray())) {
                            $transport_trackings = TransportTracking::findOrFail($transport_tracking['transport_tracking_id'][$track_key]);
                        }
                        // else {
                        //     $transport_trackings = new TransportTracking();
                        //     $transport_trackings->vendor_sale_id = $indent_request->id;
                        // }
                    } else {
                        $transport_trackings = new TransportTracking();
                        $transport_trackings->vendor_sale_id = $indent_request->id;
                    }

                    $transport_trackings->transport_type_id = $transport_tracking['transport_type_id'][$track_key];
                    $transport_trackings->transport_name = $transport_tracking['transport_name'][$track_key];
                    $transport_trackings->transport_number = $transport_tracking['transport_number'][$track_key];
                    $transport_trackings->departure_datetime = $transport_tracking['departure_datetime'][$track_key];
                    $transport_trackings->arriving_datetime = $transport_tracking['arriving_datetime'][$track_key];
                    $transport_trackings->from_location = $transport_tracking['from_location'][$track_key];
                    $transport_trackings->to_location = $transport_tracking['to_location'][$track_key];
                    if ($imageUrl != null) {
                        $transport_trackings->file = $imageUrl;
                        $transport_trackings->file_path = $imagePath;
                    }
                    $transport_trackings->save();
                }
            }
        }

        if ($request->vendor_indent_request_id != null && $request->status == 7) {
            $vendor_request = VendorIndentRequest::findOrfail($request->vendor_indent_request_id);
            $vendor_request->status = $request->status;
            $vendor_request->save();
            $exists_indent_product = VendorIndentRequestDetail::where('vendor_indent_request_id', $request->vendor_indent_request_id)->get();
            $products = $request->products;
            if (count($products) > 0) {
                foreach ($products['product_id'] as $key => $product) {
                    $product_data = Product::findOrfail($products['product_id'][$key]);
                    if (isset($products['product_id'][$key])) {
                        if (in_array($products['product_id'][$key], $exists_indent_product->pluck('product_id')->toArray())) {
                            $indent_request_detail = VendorIndentRequestDetail::where([['product_id', $products['product_id'][$key]], ['vendor_indent_request_id', $request->vendor_indent_request_id]])->first();
                        }
                        // else {
                        //     $indent_request_detail = new VendorIndentRequestDetail();
                        //     $indent_request_detail->vendor_indent_request_id = $request->vendor_indent_request_id;
                        //     $indent_request_detail->added_by_requestor = 1;
                        // }
                    } else {
                        $indent_request_detail = new VendorIndentRequestDetail();
                        $indent_request_detail->vendor_indent_request_id = $request->vendor_indent_request_id;
                        $indent_request_detail->added_by_requestor = 1;
                    }

                    $amount_with_expense = $products['amount'][$key] + $products['inc_exp_amount'][$key];
                    $amount = ((($amount_with_expense) * $products['commission_percentage'][$key]) / 100) + $amount_with_expense;

                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = $products['unit_id'][$key];
                    $indent_request_detail->request_quantity = $products['quantity'][$key];
                    $indent_request_detail->given_quantity = $products['given_quantity'][$key];
                    $indent_request_detail->amount = $amount;
                    $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                    $indent_request_detail->tax_id = $products['tax_id'][$key];
                    $indent_request_detail->tax_value = $products['tax_value'][$key];
                    $indent_request_detail->discount_type = $products['discount_type'][$key];
                    $indent_request_detail->discount_amount = $products['discount_amount'][$key] != null ? $products['discount_amount'][$key] : 0;
                    $indent_request_detail->discount_percentage = $products['discount_percentage'][$key] != null ? $products['discount_percentage'][$key] : 0;
                    $indent_request_detail->expense_amount = $products['inc_exp_amount'][$key];
                    $indent_request_detail->sub_total = $products['sub_total'][$key];

                    $indent_request_detail->save();
                }
            }

            $request_action = new VendorIndentRequestAction();
            $request_action->vendor_indent_request_id = $request->vendor_indent_request_id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = $request->remarks;
            $request_action->save();
        }

        DB::commit();

        return redirect()
            ->route('admin.customer-sales.index')
            ->with('success', 'Vendor Sale Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Vendor Sale Updated Fail');
        // }
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
