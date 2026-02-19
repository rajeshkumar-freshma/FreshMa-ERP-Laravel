<?php

namespace App\Http\Controllers\Api\HRM;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Staff;
use App\Models\StaffStoreMapping;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function employeelist(Request $request)
    {
        $store_id = $request->store_id;
        $name = $request->name;
        $staffs = Staff::whereHas('staff_store_mapping', function ($query) use ($store_id, $name) {
            if ($name != null) {
                $query
                    ->where('first_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $name . '%');
            }
            if ($store_id != null) {
                $query->where('store_id', $store_id);
            }
        })
            ->with('user_info', 'staff_store_mapping', 'staff_store_mapping.department:id,name', 'staff_store_mapping.designation:id,name')->paginate(20);

        return response()->json([
            'status' => 200,
            'data' => $staffs,
            'message' => 'Staff Details Fetched Successfully.',
        ]);
    }

    public function employeedetails(Request $request)
    {
        $staff_id = $request->staff_id;
        // $staffs = Staff::with('user_info', 'user_info.country', 'user_info.state', 'user_info.city')->findOrFail($staff_id); // old query before 01062024
        $staffs = Staff::with('user_info')->findOrFail($staff_id);
        $staff_stores = StaffStoreMapping::with('store:id,store_name,store_code,phone_number,gst_number', 'department:id,name,description,status', 'designation:id,name,status')->where([['staff_id', $staff_id], ['store_id', $request->store_id]])->first();

        return response()->json([
            'status' => 200,
            'data' => $staffs,
            'staff_stores' => $staff_stores,
            'message' => 'Staff Details Fetched Successfully.',
        ]);
    }

    public function employeeattendancelist(Request $request)
    {
        $store_id = $request->store_id;
        $attendance_date = $request->attendance_date;
        $name = $request->name;
        $staff_query = Staff::when($request, function ($query) use ($store_id, $attendance_date, $name) {
            $query->LeftJoin('admin_store_mappings', function ($join) use ($store_id) {
                $join->on('admin_store_mappings.admin_id', 'admins.id')->whereNull('admin_store_mappings.deleted_at')
                    ->where([['admin_store_mappings.store_id', $store_id], ['admin_store_mappings.status', 1]]);
            })
                ->Orwhere([['admin_store_mappings.store_id', $store_id], ['admin_store_mappings.status', 1]]);

            $query->LeftJoin('staff_store_mappings', function ($join) use ($store_id) {
                $join->on('staff_store_mappings.staff_id', 'admins.id')->whereNull('staff_store_mappings.deleted_at')
                    ->where([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
            })
                ->Orwhere([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
            $query->LeftJoin('staff_attendance_details', function ($join) use ($store_id, $attendance_date) {
                $join->on('staff_attendance_details.staff_id', 'admins.id')->whereNull('staff_attendance_details.deleted_at')
                    ->where([['staff_attendance_details.store_id', $store_id], ['staff_attendance_details.status', 1]])
                    ->whereIn('staff_attendance_details.staff_attendance_id', function ($query) use ($store_id, $attendance_date) {
                        $query->select('id')->from('staff_attendances')->whereNull('staff_attendance_details.deleted_at')->where([['attendance_date', $attendance_date], ['staff_attendances.store_id', $store_id], ['staff_attendances.status', 1]]);
                    });
            });
        });

        $staffs = $staff_query->where(function ($query) use ($name) {
            if ($name != null) {
                $query
                    ->where('first_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $name . '%');
            }
        })
            ->distinct('admins.id')
            ->select('admins.*', 'staff_attendance_details.*', 'admins.id as id')
            ->paginate(20);

        return response()->json([
            'status' => 200,
            'data' => $staffs,
            'message' => 'Staff Details Fetched Successfully.',
        ]);
    }

    public function employeecreate(Request $request)
    {
        $designation = Designation::where('status', 1)->get();
        $department = Department::where('status', 1)->get();
        $countries = Country::where('status', 1)->get();

        return response()->json([
            'status' => 200,
            'designation' => $designation,
            'department' => $department,
            'countries' => $countries,
            'message' => 'Data Fetched Successfully.',
        ]);
    }

    public function employeestore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $user = new Staff();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->phone_number);
        $user->phone_number = $request->phone_number;
        $user->user_type = 4; // 4=> Staff in admins table
        $user->status = 1;
        $user->api_token = Hash::make($request->email);
        $user->save();

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'staff');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $user_info = new UserInfo();
        $user_info->admin_type = 1; // admin_type in user_info table for refference 1=> admin
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

        // if ($request->designation_id != null && $request->department_id != null) {
        if ($user->user_type == 4) {
            $staff_store_mapping = new StaffStoreMapping();
            $staff_store_mapping->staff_id = $user->id;
            $staff_store_mapping->store_id = $request->store_id;
            $staff_store_mapping->status = 1;
            $staff_store_mapping->joined_at = $request->joined_at;
            $staff_store_mapping->designation_id = $request->designation_id;
            $staff_store_mapping->department_id = $request->department_id;
            $staff_store_mapping->remarks = $request->remarks;
            $staff_store_mapping->save();
            // }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Data Stored Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Employee Stored Fail');
        // }
    }

    public function employeeupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
            Log::info($request);
            $staff_id = $request->staff_id;
            $user = Staff::findOrFail($staff_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->phone_number);
            $user->phone_number = $request->phone_number;
            $user->user_type = 4; // 4=> Staff in admins table
            $user->status = $request->status;
            $user->api_token = Hash::make($request->email);
            $user->save();

            $imagePath = null;
            $imageUrl = null;
            if ($request->hasFile('image')) {
                $imageData = CommonComponent::s3BucketFileUpload($request->image, 'staff');
                $imagePath = $imageData['filePath'];
                // $imageUrl = $imageData['fileName'];
                $imageUrl = $imageData['imageURL'];
            }

            $user_info = UserInfo::where([['admin_type', 1], ['admin_id', $user->id]])->first();
            if ($user_info == null) {
                $user_info = new UserInfo();
            }
            $user_info->admin_type = 1; // admin_type in user_info table for refference 1=> admin
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

            if ($request->designation_id != null && $request->department_id != null) {
                if ($user->user_type == 4) {
                    $staff_store_mapping = StaffStoreMapping::where([['staff_id', $staff_id], ['store_id', $request->store_id]])->first();
                    if ($staff_store_mapping == null) {
                        $staff_store_mapping = new StaffStoreMapping();
                    }
                    $staff_store_mapping->staff_id = $user->id;
                    $staff_store_mapping->store_id = $request->store_id;
                    $staff_store_mapping->status = $request->status;
                    $staff_store_mapping->joined_at = $request->joined_at;
                    $staff_store_mapping->designation_id = $request->designation_id;
                    $staff_store_mapping->department_id = $request->department_id;
                    $staff_store_mapping->remarks = $request->remarks;
                    $staff_store_mapping->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data Updated Successfully.',
            ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Data Stored Fail');
        // }
    }
}
