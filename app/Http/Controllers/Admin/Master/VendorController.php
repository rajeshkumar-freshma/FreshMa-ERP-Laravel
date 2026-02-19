<?php

namespace App\Http\Controllers\Admin\Master;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\VendorDataTable;
use App\Http\Requests\Master\VendorFormRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Vendor;
use App\Models\UserInfo;
use App\Models\Country;
use App\Models\Currency;
use App\Models\SalesOrder;
use App\Models\VendorDetail;
use App\Models\VendorCommissionDetailHistory;
use App\Models\VendorIndentRequest;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VendorDataTable $dataTable)
    {
        return $dataTable->render('pages.master.vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::where('status', 1)->get();
        $data['currencies'] = Currency::where('status', 1)->get();
        return view('pages.master.vendor.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorFormRequest $request)
    {
        DB::beginTransaction();

        // try {
        $user = new Vendor();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->user_code = $request->user_code;
        $user->user_type = $request->user_type; // 1=> Vendor in admins table
        $user->status = $request->status;
        $user->save();


        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageData = commoncomponent::s3BucketFileUpload($request->image, 'vendor');
            Log::info("imageData");
            Log::info($imageData);
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        Log::info("imageUrl");
        Log::info($imageUrl);
        $user_info = new UserInfo();
        $user_info->admin_type = 2; //admin_type in user_info table for refference 2=> user
        $user_info->user_id = $user->id;
        $user_info->company = $request->company;
        $user_info->website = $request->website;
        $user_info->address = $request->address;
        $user_info->country_id = $request->country_id;
        $user_info->state_id = $request->state_id;
        $user_info->city_id = $request->city_id;
        $user_info->currency_id = $request->currency_id;
        $user_info->gst_number = $request->gst_number;
        $user_info->joined_at = $request->joined_at;
        if ($imageUrl != null) {
            $user_info->image = $imageUrl;
            $user_info->image_path = $imagePath;
        }
        $user_info->save();
        Log::info("user_info");
        Log::info($user_info);
        if ($request->vendor_commission != null && $request->user_type == 1) {
            $vendor_detail = new VendorDetail();
            $vendor_detail->vendor_id = $user->id;
            $vendor_detail->vendor_commission = $request->vendor_commission;
            $vendor_detail->it_can_edit_on_billing = $request->it_can_edit_on_billing;
            $vendor_detail->remarks = $request->remarks;
            $vendor_detail->save();
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.customer.index')->with('success', 'Vendor Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Vendor Store Successfully');
        }
        // }  catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Vendor Stored Fail');
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
        $data = $common_data = $this->customer_overview($id);
        return view('pages.master.vendor.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['countries'] = Country::where('status', 1)->get();
        $data['currencies'] = Currency::where('status', 1)->get();
        $data['user'] = Vendor::findOrfail($id);
        return view('pages.master.vendor.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VendorFormRequest $request, $id)
    {
        // return $request->all();
        DB::beginTransaction();
        // try {
        $user = Vendor::findOrfail($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->phone_number = $request->phone_number;
        $user->user_code = $request->user_code;
        $user->user_type = $request->user_type; // 1=> Vendor in admins table
        $user->status = $request->status;
        $user->save();


        $user_info = UserInfo::where([['admin_type', 2], ['user_id', $user->id]])->first();
        if ($user_info == null) {
            $user_info = new UserInfo();
        }

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->image, $user_info->image_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'vendor');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $user_info->admin_type = 2; //admin_type in user_info table for refference 2=> user
        $user_info->user_id = $user->id;
        $user_info->company = $request->company;
        $user_info->website = $request->website;
        $user_info->address = $request->address;
        $user_info->country_id = $request->country_id;
        $user_info->state_id = $request->state_id;
        $user_info->city_id = $request->city_id;
        $user_info->currency_id = $request->currency_id;
        $user_info->gst_number = $request->gst_number;
        $user_info->joined_at = $request->joined_at;
        if ($imageUrl != null) {
            $user_info->image = $imageUrl;
            $user_info->image_path = $imagePath;
        }
        $user_info->save();

        $vendor_detail = VendorDetail::where('vendor_id', $user->id)->first();
        if ($vendor_detail != null && $vendor_detail->vendor_commission != $request->vendor_commission) {
            $history = new VendorCommissionDetailHistory();
            $history->vendor_id = $user->id;
            $history->old_vendor_commission = $vendor_detail->vendor_commission;
            $history->new_vendor_commission = $request->vendor_commission;
            $history->remarks = $request->remarks;
            $history->save();
        }

        if ($request->vendor_commission != null) {
            if ($vendor_detail == null) {
                $vendor_detail = new VendorDetail();
                $vendor_detail->vendor_id = $user->id;
            }
            $vendor_detail->vendor_commission = $request->vendor_commission;
            $vendor_detail->it_can_edit_on_billing = isset($request->it_can_edit_on_billing) ? $request->it_can_edit_on_billing : 0;
            $vendor_detail->remarks = $request->remarks;
            $vendor_detail->save();
        }

        DB::commit();

        return redirect()->route('admin.customer.index')->with('success', 'Vendor Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Vendor Updatedd Fail');
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
            $user = Vendor::findOrFail($id);

            $fileDeleted = commoncomponent::s3BucketFileDelete($user->user_info->image, $user->user_info->image_path);

            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }

    // Vendor Details get
    public function customer_overview($id)
    {
        $data['users'] = Vendor::findOrFail($id);
        $data['totalAmount'] = SalesOrder::where('vendor_id', $id)->sum('total_amount');
        $data['vendor_totalAmount'] = VendorIndentRequest::where('vendor_id', $id)->sum('total_amount');
        return $data;
    }

    // sales order customer wise get
    public function salesorder_table($id)
    {

        $data = $common_data = $this->customer_overview($id);
        $data['sales_orders'] = SalesOrder::where('vendor_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.master.vendor.salesorder_table', $data);
    }

    // Vendor intent Request Details

    public function Customer_intent_table($id)
    {
        $data = $common_data = $this->customer_overview($id);
        $data['customer_tables'] = VendorIndentRequest::where('vendor_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.master.vendor.customer_intent_table', $data);
    }

    // customer paymetn transacction

    public function Customer_payment_transaction($id)
    {
        $data = $common_data = $this->customer_overview($id);
        $data['sales'] = SalesOrder::where('vendor_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.master.vendor.payment_transaction', $data);
    }
}
