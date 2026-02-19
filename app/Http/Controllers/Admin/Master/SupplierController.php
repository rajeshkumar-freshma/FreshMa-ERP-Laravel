<?php

namespace App\Http\Controllers\Admin\Master;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\SupplierDataTable;
use App\Http\Requests\Master\SupplierFormRequest;
use App\Jobs\PaymentSuccessJob;
use Illuminate\Support\Facades\Hash;
use App\Models\Supplier;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PurchaseOrder;
use App\Models\UserInfo;
use App\Models\SalaryDetail;
use App\Models\WarehouseIndentRequest;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupplierDataTable $dataTable)
    {
        return $dataTable->render('pages.master.supplier.index');
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
        return view('pages.master.supplier.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        $user = new Supplier();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->user_code = $request->user_code;
        $user->user_type = 2; // 2=> Supplier in users table
        $user->api_token = Hash::make($request->email);
        $user->status = $request->status;
        $user->save();


        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'supplier');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

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

        $user_info->pan_number = $request->pan_number;
        $user_info->aadhar_number = $request->aadhar_number;
        $user_info->esi_number = $request->esi_number;
        $user_info->pf_number = $request->pf_number;
        $user_info->account_number = $request->account_number;
        $user_info->bank_name = $request->bank_name;
        $user_info->name_as_per_record = $request->name_as_per_record;
        $user_info->branch_name = $request->branch_name;
        $user_info->ifsc_code = $request->ifsc_code;

        if ($request->hasFile('pan_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->pan_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pan_file = $imageUrl;
            $user_info->pan_file_path = $imagePath;
        }

        if ($request->hasFile('aadhar_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->aadhar_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->aadhar_file = $imageUrl;
            $user_info->aadhar_file_path = $imagePath;
        }

        if ($request->hasFile('esi_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->esi_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->esi_file = $imageUrl;
            $user_info->esi_file_path = $imagePath;
        }

        if ($request->hasFile('pf_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->pf_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pf_file = $imageUrl;
            $user_info->pf_file_path = $imagePath;
        }

        if ($request->hasFile('bank_passbook_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->bank_passbook_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->bank_passbook_file = $imageUrl;
            $user_info->bank_passbook_file_path = $imagePath;
        }
        $user_info->save();

        $salary_detail = new SalaryDetail();
        $salary_detail->admin_type = 2; //admin_type in user_info table for refference 2=> user
        $salary_detail->user_id = $user->id;
        $salary_detail->salary_type = $request->salary_type;
        $salary_detail->amount_type = $request->amount_type;
        $salary_detail->amount = $request->amount;
        $salary_detail->percentage = $request->percentage;
        $salary_detail->remarks = $request->remarks;
        $salary_detail->save();
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.supplier.index')->with('success', 'Supplier Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Supplier Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Supplier Stored Fail');
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
        $data = $common_data = $this->supplier_overview($id);
        // PaymentSuccessJob::dispatch($id);
        return view('pages.master.supplier.show', $data);
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
        $data['supplier'] = Supplier::findOrfail($id);
        return view('pages.master.supplier.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $user = Supplier::findorfail($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->user_code = $request->user_code;
        $user->user_type = 2; // 2=> Supplier in users table
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

            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'supplier');
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

        $user_info->pan_number = $request->pan_number;
        $user_info->aadhar_number = $request->aadhar_number;
        $user_info->esi_number = $request->esi_number;
        $user_info->pf_number = $request->pf_number;
        $user_info->account_number = $request->account_number;
        $user_info->bank_name = $request->bank_name;
        $user_info->name_as_per_record = $request->name_as_per_record;
        $user_info->branch_name = $request->branch_name;
        $user_info->ifsc_code = $request->ifsc_code;

        if ($request->hasFile('pan_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->pan_file, $user_info->pan_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->pan_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pan_file = $imageUrl;
            $user_info->pan_file_path = $imagePath;
        }

        if ($request->hasFile('aadhar_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->aadhar_file, $user_info->aadhar_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->aadhar_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->aadhar_file = $imageUrl;
            $user_info->aadhar_file_path = $imagePath;
        }

        if ($request->hasFile('esi_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->esi_file, $user_info->esi_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->esi_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->esi_file = $imageUrl;
            $user_info->esi_file_path = $imagePath;
        }

        if ($request->hasFile('pf_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->pf_file, $user_info->pf_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->pf_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pf_file = $imageUrl;
            $user_info->pf_file_path = $imagePath;
        }

        if ($request->hasFile('bank_passbook_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->bank_passbook_file, $user_info->bank_passbook_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->bank_passbook_document, 'supplier');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->bank_passbook_file = $imageUrl;
            $user_info->bank_passbook_file_path = $imagePath;
        }
        $user_info->save();

        $salary_detail = SalaryDetail::where([['admin_type', 2], ['user_id', $user->id]])->first();
        if ($salary_detail == null) {
            $salary_detail = new SalaryDetail();
        }
        $salary_detail->admin_type = 2; //admin_type in user_info table for refference 2=> user
        $salary_detail->salary_type = $request->salary_type;
        $salary_detail->user_id = $user->id;
        $salary_detail->amount_type = $request->amount_type;
        $salary_detail->amount = $request->amount;
        $salary_detail->percentage = $request->percentage;
        $salary_detail->remarks = $request->remarks;
        $salary_detail->save();

        DB::commit();

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Supplier Update Fail');
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
            $supplier = Supplier::findOrFail($id);

            $fileDeleted = CommonComponent::s3BucketFileDelete($supplier->user_info->image, $supplier->user_info->image_path);

            $supplier->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Supplier Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }

    public function supplier_purchase($id)
    {
        $data = $common_data = $this->supplier_overview($id);
        $data['purchases'] = PurchaseOrder::where('supplier_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.master.supplier.purchse_table', $data);
    }

    public function supplier_warehouse($id)
    {
        $data = $common_data = $this->supplier_overview($id);
        $data['warehouse'] = WarehouseIndentRequest::where('supplier_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.master.supplier.warehouse_table', $data);
    }
    public function supplier_payment_details($id)
    {
        $data = $common_data = $this->supplier_overview($id);
        $data['purchases'] = PurchaseOrder::where('supplier_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.master.supplier.payment_transaction', $data);
    }

    public function supplier_overview($id)
    {
        $data['users'] = Supplier::findOrFail($id);
        $data['totalAmount'] = PurchaseOrder::where('supplier_id', $id)->sum('total');
        $data['warehouse_totalAmount'] = WarehouseIndentRequest::where('supplier_id', $id)->sum('total_amount');
        return $data;
    }
}
