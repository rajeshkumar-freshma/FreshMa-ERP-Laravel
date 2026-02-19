<?php

namespace App\Http\Controllers\Admin\IndentRequest;

use App\Core\CommonComponent;
use App\DataTables\IndentRequest\VendorIndentRequestDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndentRequest\VendorIndentFormRequest;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorIndentRequest;
use App\Models\VendorIndentRequestAction;
use App\Models\VendorIndentRequestDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VendorIndentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VendorIndentRequestDataTable $dataTable)
    {
        return $dataTable->render('pages.indent_request.vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')->active()->get();
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        return view('pages.indent_request.vendor.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorIndentFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'vendor_indent');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request = new VendorIndentRequest();
        $indent_request->vendor_id = $request->vendor_id;
        $indent_request->request_code = $request->ir_code;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $indent_request_detail = new VendorIndentRequestDetail();
                $indent_request_detail->vendor_indent_request_id = $indent_request->id;
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = @$products['unit_id'][$key];
                $indent_request_detail->request_quantity = @$products['quantity'][$key];
                $indent_request_detail->amount = @$products['amount'][$key];
                $indent_request_detail->sub_total = @$products['sub_total'][$key];
                $indent_request_detail->added_by_requestor = 1;
                $indent_request_detail->save();
            }
        }

        $request_action = new VendorIndentRequestAction();
        $request_action->vendor_indent_request_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.customer-indent-request.index')->with('success', 'Vendor Indent Request Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Vendor Indent Request Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Vendor Indent Request Stored Fail');
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
        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')->active()->get();
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['indent_request'] = VendorIndentRequest::findOrfail($id);
        return view('pages.indent_request.vendor.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VendorIndentFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        $indent_request = VendorIndentRequest::findOrfail($id);
        if ($request->hasFile('file')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($indent_request->file, $indent_request->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'vendor_indent');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request->vendor_id = $request->vendor_id;
        $indent_request->request_code = $request->ir_code;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        // $request_old_ids = [];
        // foreach ($request->products['product_id'] as $store_key => $value) {
        //     if ($request->products['id'][$store_key] != null) {
        //         $request_old_ids[] = $request->products['id'][$store_key];
        //     }
        // }
        $request_old_ids = [];
        if (isset($request->products['product_id']) && is_array($request->products['product_id'])) {
            foreach ($request->products['product_id'] as $store_key => $value) {
                if (isset($request->products['id'][$store_key]) && $request->products['id'][$store_key] != null) {
                    $request_old_ids[] = $request->products['id'][$store_key];
                }
            }
        }

        $exists_indent_product = VendorIndentRequestDetail::where('vendor_indent_request_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    VendorIndentRequestDetail::where('id', $value->id)->delete();
                }
            }
        }

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $indent_request_detail = VendorIndentRequestDetail::findOrFail($products['id'][$key]);
                    }
                    // else {
                    //     $indent_request_detail = new VendorIndentRequestDetail();
                    //     $indent_request_detail->vendor_indent_request_id = $indent_request->id;
                    // }
                } else {
                    $indent_request_detail = new VendorIndentRequestDetail();
                    $indent_request_detail->vendor_indent_request_id = $indent_request->id;
                }
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = $products['unit_id'][$key];
                $indent_request_detail->request_quantity = $products['quantity'][$key];
                $indent_request_detail->amount = $products['amount'][$key];
                $indent_request_detail->sub_total = $products['sub_total'][$key];
                $indent_request_detail->added_by_requestor = 1;
                $indent_request_detail->save();
            }
        }

        $request_action = new VendorIndentRequestAction();
        $request_action->vendor_indent_request_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();

        return redirect()->route('admin.customer-indent-request.index')->with('success', 'Vendor Indent Request Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Vendor Indent Request Updated Fail');
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
        try {
            VendorIndentRequestDetail::where('vendor_indent_request_id', $id)->delete();
            $indent_request = VendorIndentRequest::findOrFail($id);

            $indent_request->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Indent request Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
