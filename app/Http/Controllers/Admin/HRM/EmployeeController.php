<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Core\CommonComponent;
use App\DataTables\HRM\EmployeeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\EmployeeFormRequest;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Staff;
use App\Models\StaffStoreMapping;
use App\Models\Store;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::where('status', 1)->get();
        $data['stores'] = Store::where('status', 1)->get();
        $data['departments'] = Department::where('status', 1)->get();
        $data['designations'] = Designation::where('status', 1)->get();
        return view('pages.hrm.employee.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        // return $request->all();
        $user = new Staff();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->user_type = 4; // 4=> Staff in admins table
        $user->status = $request->status;
        $user->api_token = Hash::make($request->email);
        $user->save();

        $user_info = new UserInfo();
        $user_info->admin_type = 1; // admin_type in user_info table for refference 1=> admin
        $user_info->admin_id = $user->id;
        $user_info->address = $request->address;
        $user_info->country_id = $request->country_id;
        $user_info->state_id = $request->state_id;
        $user_info->city_id = $request->city_id;
        $user_info->joined_at = $request->joined_at;

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }
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
            $imageData = CommonComponent::s3BucketFileUpload($request->pan_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pan_file = $imageUrl;
            $user_info->pan_file_path = $imagePath;
        }

        if ($request->hasFile('aadhar_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->aadhar_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->aadhar_file = $imageUrl;
            $user_info->aadhar_file_path = $imagePath;
        }

        if ($request->hasFile('esi_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->esi_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->esi_file = $imageUrl;
            $user_info->esi_file_path = $imagePath;
        }

        if ($request->hasFile('pf_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->pf_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pf_file = $imageUrl;
            $user_info->pf_file_path = $imagePath;
        }

        if ($request->hasFile('bank_passbook_document')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->bank_passbook_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->bank_passbook_file = $imageUrl;
            $user_info->bank_passbook_file_path = $imagePath;
        }
        $user_info->save();

        if (count($request->employee_store) > 0) {
            foreach ($request->employee_store as $key => $value) {
                if ($request->employee_store[$key]['store_id'] != null) {
                    if ($user->user_type == 4) {
                        $staff_store_mapping = new StaffStoreMapping();
                        $staff_store_mapping->staff_id = $user->id;
                        $staff_store_mapping->store_id = $request->employee_store[$key]['store_id'];
                        $staff_store_mapping->status = $request->employee_store[$key]['status'];
                        $staff_store_mapping->joined_at = $request->employee_store[$key]['joined_at'] ?? '';
                        $staff_store_mapping->designation_id = $request->employee_store[$key]['designation_id'];
                        $staff_store_mapping->department_id = $request->employee_store[$key]['department_id'];
                        $staff_store_mapping->remarks = $request->employee_store[$key]['remarks'];
                        $staff_store_mapping->save();
                    }
                }
            }
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.employee.index')->with('success', 'Employee Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Employee Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Employee Stored Fail');
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
        try {
            $data['employee'] = Staff::with(['user_info', 'staff_store_mapping.admin'])->find($id);
            return view('pages.hrm.employee.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['staff'] = Staff::findOrFail($id);
        $data['countries'] = Country::where('status', 1)->get();
        $data['stores'] = Store::where('status', 1)->get();
        $data['departments'] = Department::where('status', 1)->get();
        $data['designations'] = Designation::where('status', 1)->get();
        return view('pages.hrm.employee.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $user = Staff::findOrfail($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->phone_number = $request->phone_number;
        $user->status = $request->status;
        $user->save();

        $user_info = UserInfo::where([['admin_type', 1], ['admin_id', $user->id]])->first();
        if ($user_info == null) {
            $user_info = new UserInfo();
        }

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->image, $user_info->image_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $user_info->admin_type = 1; //admin_type in user_info table for refference 1=> admin
        $user_info->admin_id = $user->id;
        $user_info->address = $request->address;
        $user_info->country_id = $request->country_id;
        $user_info->state_id = $request->state_id;
        $user_info->city_id = $request->city_id;
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

            $imageData = CommonComponent::s3BucketFileUpload($request->pan_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pan_file = $imageUrl;
            $user_info->pan_file_path = $imagePath;
        }

        if ($request->hasFile('aadhar_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->aadhar_file, $user_info->aadhar_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->aadhar_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->aadhar_file = $imageUrl;
            $user_info->aadhar_file_path = $imagePath;
        }

        if ($request->hasFile('esi_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->esi_file, $user_info->esi_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->esi_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->esi_file = $imageUrl;
            $user_info->esi_file_path = $imagePath;
        }

        if ($request->hasFile('pf_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->pf_file, $user_info->pf_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->pf_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->pf_file = $imageUrl;
            $user_info->pf_file_path = $imagePath;
        }

        if ($request->hasFile('bank_passbook_document')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($user_info->bank_passbook_file, $user_info->bank_passbook_file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->bank_passbook_document, 'staff');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            $user_info->bank_passbook_file = $imageUrl;
            $user_info->bank_passbook_file_path = $imagePath;
        }

        $user_info->save();

        $request_old_ids = [];
        foreach ($request->employee_store as $store_key => $value) {
            if (isset($request->employee_store[$store_key]['id'])) {
                $request_old_ids[] = $request->employee_store[$store_key]['id'];
            }
        }

        $exists_staffstore_data = StaffStoreMapping::where('staff_id', $id)->get();
        if ($request->user_type == 4) {
            if (count($exists_staffstore_data) > 0) {
                foreach ($exists_staffstore_data as $exists_key => $value) {
                    if (!in_array($value->id, $request_old_ids)) {
                        StaffStoreMapping::where('id', $value->id)->delete();
                    }
                }
            }
        }

        if (count($request->employee_store) > 0) {
            foreach ($request->employee_store as $store_key => $value) {
                if ($request->employee_store[$store_key]['store_id'] != null) {
                    if (isset($value['id'])) {
                        if (in_array($value['id'], $exists_staffstore_data->pluck('id')->toArray())) {
                            if ($user->user_type == 4) {
                                $staff_store_mapping = StaffStoreMapping::findOrFail($value['id']);
                                $staff_store_mapping->designation_id = $request->employee_store[$store_key]['designation_id'];
                            }
                            $staff_store_mapping->store_id = $request->employee_store[$store_key]['store_id'];
                            $staff_store_mapping->status = $request->employee_store[$store_key]['status'];
                            $staff_store_mapping->joined_at = $request->employee_store[$store_key]['joined_at'];
                            $staff_store_mapping->remarks = $request->employee_store[$store_key]['remarks'];
                            $staff_store_mapping->save();
                        }
                    } else {
                        if ($user->user_type == 4) {
                            $staff_store_mapping = new StaffStoreMapping();
                            $staff_store_mapping->staff_id = $user->id;
                            $staff_store_mapping->store_id = $request->employee_store[$store_key]['store_id'];
                            $staff_store_mapping->status = $request->employee_store[$store_key]['status'];
                            $staff_store_mapping->joined_at = $request->employee_store[$store_key]['joined_at'];
                            $staff_store_mapping->designation_id = $request->employee_store[$store_key]['designation_id'];
                            $staff_store_mapping->department_id = $request->employee_store[$store_key]['department_id'];
                            $staff_store_mapping->remarks = $request->employee_store[$store_key]['remarks'];
                            $staff_store_mapping->save();
                        }
                    }
                }
            }
        }
        DB::commit();

        return redirect()->route('admin.employee.index')->with('success', 'Staff Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Staff Updatedd Fail');
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
