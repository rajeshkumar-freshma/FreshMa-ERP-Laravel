<?php

namespace App\Http\Controllers\Admin\Master;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\PartnerDataTable;
use App\Http\Requests\Master\PartnerFormRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Partner;
use App\Models\UserInfo;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PartnershipType;
use App\Models\Store;
use App\Models\PartnershipDetail;
use App\Models\AdminStoreMapping;
use App\Models\AdminWarehouseMapping;
use App\Models\StaffStoreMapping;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PartnerDataTable $dataTable)
    {
        return $dataTable->render('pages.master.partner.index');
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
        $data['partnership_types'] = PartnershipType::where('status', 1)->get();
        $data['stores'] = Store::where('status', 1)->get();
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        $data['roles'] = Role::get();
        return view('pages.master.partner.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerFormRequest $request)
    {
        DB::beginTransaction();

        // try {
        $user = new Partner();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name ?? '';
        $user->email = $request->email ?? '';
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->user_type = $request->user_type;
        $user->user_code = $request->user_code;
        $user->status = $request->status;
        $user->role_id = $request->role_id;
        $user->api_token = Hash::make($request->email);
        $user->save();
        // return$request->role_id;
        //   return  $data['roles'] = Role::get();
        // $role = Role::find($request->role_id);
        $user->syncRoles($request->role_id);

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'vendor');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $user_info = new UserInfo();
        $user_info->admin_type = 1;
        $user_info->admin_id = $user->id;
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

        if (!empty($request->partnership_store) && is_array($request->partnership_store) && count($request->partnership_store) > 0) {
            foreach ($request->partnership_store as $key => $value) {
                // Check if 'id' key exists before accessing it
                // if (isset($request->partnership_store[$key]['id'])) {
                if ($request->user_type == 3 && $request->partnership_store[$key]['store_id'] != null) {
                    $partnership_detail = new PartnershipDetail();
                    $partnership_detail->partner_id = $user->id;
                    $partnership_detail->partnership_type_id = isset($request->partnership_store[$key]['partnership_type_id']) ? $request->partnership_store[$key]['partnership_type_id'] : null;
                    $partnership_detail->store_id = $request->partnership_store[$key]['store_id'] ?? '';
                    $partnership_detail->status = $request->partnership_store[$key]['status'] ?? '';
                    $partnership_detail->joined_at = $request->partnership_store[$key]['joined_at'] ?? '';
                    $partnership_detail->remarks = $request->partnership_store[$key]['remarks'];
                    $partnership_detail->save();
                }
                if ($request->user_type == 2) {
                    if (isset($request->partnership_store[$key]['store_id']) && !is_null($request->partnership_store[$key]['store_id'])) {
                        $admin_store_mapping = new AdminStoreMapping();
                        $admin_store_mapping->admin_id = $user->id;
                        $admin_store_mapping->store_id = $request->partnership_store[$key]['store_id'] ? $request->partnership_store[$key]['store_id'] : null;
                        $admin_store_mapping->status = $request->partnership_store[$key]['status'];
                        $admin_store_mapping->assigned_at = $request->partnership_store[$key]['joined_at'] ? $request->partnership_store[$key]['joined_at'] : null;
                        $admin_store_mapping->remarks = $request->partnership_store[$key]['remarks'];
                        $admin_store_mapping->save();
                    }
                }
                if ($request->user_type == 4 && $request->partnership_store[$key]['store_id'] != null) {
                    $staff_store_mapping = new StaffStoreMapping();
                    $staff_store_mapping->staff_id = $user->id;
                    $staff_store_mapping->store_id = $request->partnership_store[$key]['store_id'] !== null ? $request->partnership_store[$key]['store_id'] : '';
                    $staff_store_mapping->status = $request->partnership_store[$key]['status'] ?? 1;
                    $staff_store_mapping->joined_at = $request->partnership_store[$key]['joined_at'] != null ? $request->partnership_store[$key]['joined_at'] : '';
                    $staff_store_mapping->designation_id = @$request->partnership_store[$key]['designation_id'];
                    $staff_store_mapping->remarks = $request->partnership_store[$key]['remarks'];
                    $staff_store_mapping->save();
                }
                // }
            }
        }

        if (!empty($request->warehouse_assign) && is_array($request->warehouse_assign)) {
            foreach ($request->warehouse_assign as $key => $value) {
                if ($request->user_type == 2) {
                    $admin_store_mapping = new AdminWarehouseMapping();
                    $admin_store_mapping->admin_id = $user->id;
                    $admin_store_mapping->warehouse_id = $request->warehouse_assign[$key]['warehouse_id'] ?? null;
                    $admin_store_mapping->status = $request->warehouse_assign[$key]['status'] ?? '';
                    $admin_store_mapping->assigned_at = $request->warehouse_assign[$key]['joined_at'] ?? '';
                    $admin_store_mapping->remarks = $request->warehouse_assign[$key]['remarks'];
                    $admin_store_mapping->save();
                }
            }
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.partner.index')->with('success', 'Partner Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Partner Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Partner Stored Fail');
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
            $data['partner'] = Partner::findOrFail($id);
            return view('pages.master.partner.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
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
        $data['countries'] = Country::where('status', 1)->get();
        $data['currencies'] = Currency::where('status', 1)->get();
        $data['partnership_types'] = PartnershipType::where('status', 1)->get();
        $data['stores'] = Store::where('status', 1)->get();
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        $data['partner'] = Partner::with('partnership_detail')->findOrfail($id);
        $data['roles'] = Role::get();
        return view('pages.master.partner.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerFormRequest $request, $id)
    {
        // return $request->all();
        DB::beginTransaction();
        // try {
        $user = Partner::findOrfail($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->phone_number = $request->phone_number;
        $user->user_type = $request->user_type; // 2=> Partner in admins table
        $user->user_code = $request->user_code;
        $user->status = $request->status;
        $user->role_id = $request->role_id;
        $user->save();

        // $role = Role::find($request->role_id);
        $user->syncRoles($request->role_id);

        $user_info = UserInfo::where([['admin_type', 1], ['admin_id', $user->id]])->first();
        if ($user_info == null) {
            $user_info = new UserInfo();
        }

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $fileDeleted = commoncomponent::s3BucketFileDelete($user_info->image, $user_info->image_path);

            $imageData = commoncomponent::s3BucketFileUpload($request->image, 'vendor');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $user_info->admin_type = 1; //admin_type in user_info table for refference 1=> admin
        $user_info->admin_id = $user->id;
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

        $request_old_ids = [];
        if (isset($request->partnership_store) && count($request->partnership_store) > 0) {
            foreach ($request->partnership_store as $store_key => $value) {
                if (isset($request->partnership_store[$store_key]['id'])) {
                    $request_old_ids[] = $request->partnership_store[$store_key]['id'];
                }
            }
        }

        if ($request->user_type == 3) {
            $exists_partnership_data = PartnershipDetail::where('partner_id', $id)->get();
            if (count($exists_partnership_data) > 0) {
                foreach ($exists_partnership_data as $exists_key => $value) {
                    if (!in_array($value->id, $request_old_ids)) {
                        PartnershipDetail::where('id', $value->id)->delete();
                    }
                }
            }
        }

        if ($request->user_type == 1 || $request->user_type == 2) {
            $exists_partnership_data = AdminStoreMapping::where('admin_id', $id)->get();
            if (count($exists_partnership_data) > 0) {
                foreach ($exists_partnership_data as $exists_key => $value) {
                    if (!in_array($value->id, $request_old_ids)) {
                        AdminStoreMapping::where('id', $value->id)->delete();
                    }
                }
            }
        }

        if ($request->user_type == 4) {
            $exists_partnership_data = StaffStoreMapping::where('staff_id', $id)->get();
            if (count($exists_partnership_data) > 0) {
                foreach ($exists_partnership_data as $exists_key => $value) {
                    if (!in_array($value->id, $request_old_ids)) {
                        StaffStoreMapping::where('id', $value->id)->delete();
                    }
                }
            }
        }

        if (isset($request->partnership_store) && count($request->partnership_store) > 0) {
            foreach ($request->partnership_store as $store_key => $value) {
                if (isset($value['store_id'])) {
                    if (isset($value['id'])) {
                        if (in_array($value['id'], $exists_partnership_data->pluck('id')->toArray())) {
                            if ($request->user_type == 3) {
                                $partnership_detail = PartnershipDetail::findOrFail($value['id']);
                                $partnership_detail->partnership_type_id = $request->partnership_store[$store_key]['partnership_type_id'];
                                $partnership_detail->joined_at = $request->partnership_store[$store_key]['joined_at'] ?? null;
                            }
                            if ($request->user_type == 1 || $request->user_type == 2) {
                                $partnership_detail = AdminStoreMapping::findOrFail($value['id']);
                                $partnership_detail->assigned_at = $request->partnership_store[$store_key]['joined_at'] ?? null;
                            }
                            if ($request->user_type == 4) {
                                $partnership_detail = StaffStoreMapping::findOrFail($value['id']);
                                $partnership_detail->designation_id = @$request->partnership_store[$store_key]['designation_id'];
                                $partnership_detail->joined_at = $request->partnership_store[$store_key]['joined_at'] ?? null;
                            }
                            $partnership_detail->store_id = $request->partnership_store[$store_key]['store_id'];
                            $partnership_detail->status = $request->partnership_store[$store_key]['status'];
                            $partnership_detail->remarks = $request->partnership_store[$store_key]['remarks'];
                            $partnership_detail->save();
                        }
                    } else {
                        if ($request->user_type == 2) {
                            $admin_store_mapping = new AdminStoreMapping();
                            $admin_store_mapping->admin_id = $user->id;
                            $admin_store_mapping->store_id = $request->partnership_store[$store_key]['store_id'] ?? '';
                            $admin_store_mapping->status = $request->partnership_store[$store_key]['status'] ?? '';
                            $admin_store_mapping->assigned_at = $request->partnership_store[$store_key]['joined_at'] ?? '';
                            $admin_store_mapping->remarks = $request->partnership_store[$store_key]['remarks'];
                            $admin_store_mapping->save();
                        }
                        if ($request->user_type == 3) {
                            $partnership_detail = new PartnershipDetail();
                            $partnership_detail->partner_id = $user->id;
                            $partnership_detail->partnership_type_id = $request->partnership_store[$store_key]['partnership_type_id'];
                            $partnership_detail->store_id = $request->partnership_store[$store_key]['store_id'];
                            $partnership_detail->status = $request->partnership_store[$store_key]['status'];
                            $partnership_detail->joined_at = $request->partnership_store[$store_key]['joined_at'] ?? '';
                            $partnership_detail->remarks = $request->partnership_store[$store_key]['remarks'];
                            $partnership_detail->save();
                        }
                        if ($request->user_type == 4) {
                            $staff_store_mapping = new StaffStoreMapping();
                            $staff_store_mapping->staff_id = $user->id;
                            $staff_store_mapping->store_id = $request->partnership_store[$store_key]['store_id'];
                            $staff_store_mapping->status = $request->partnership_store[$store_key]['status'];
                            $staff_store_mapping->joined_at = $request->partnership_store[$store_key]['joined_at'] ?? '';
                            $staff_store_mapping->designation_id = @$request->partnership_store[$store_key]['designation_id'];
                            $staff_store_mapping->remarks = $request->partnership_store[$store_key]['remarks'];
                            $staff_store_mapping->save();
                        }
                    }
                }
            }
        }

        $request_old_awm_ids = [];
        if (isset($request->warehouse_assign) && count($request->warehouse_assign) > 0) {
            foreach ($request->warehouse_assign as $store_key => $value) {
                if (isset($request->warehouse_assign[$store_key]['id'])) {
                    $request_old_awm_ids[] = $request->warehouse_assign[$store_key]['id'];
                }
            }
        }

        $exists_admin_warehouse_data = AdminWarehouseMapping::where('admin_id', $id)->get();
        if (count($exists_admin_warehouse_data) > 0) {
            foreach ($exists_admin_warehouse_data as $exists_key => $value) {
                if (!in_array($value->id, $request_old_awm_ids)) {
                    AdminWarehouseMapping::where('id', $value->id)->delete();
                }
            }
        }

        if (isset($request->warehouse_assign) && count($request->warehouse_assign) > 0) {
            foreach ($request->warehouse_assign as $key => $value) {
                if ($value['warehouse_id'] != null) {
                    if (isset($value['id'])) {
                        if (in_array($value['id'], $exists_admin_warehouse_data->pluck('id')->toArray())) {
                            $admin_warehouse_mapping = AdminWarehouseMapping::findOrFail($value['id']);
                        }
                    } else {
                        $admin_warehouse_mapping = new AdminWarehouseMapping();
                    }
                    $admin_warehouse_mapping->admin_id = $user->id;
                    $admin_warehouse_mapping->warehouse_id = $request->warehouse_assign[$key]['warehouse_id'] ?? null;
                    $admin_warehouse_mapping->status = $request->warehouse_assign[$key]['status'] ?? '';
                    $admin_warehouse_mapping->assigned_at = $request->warehouse_assign[$key]['joined_at'] ?? '';
                    $admin_warehouse_mapping->remarks = $request->warehouse_assign[$key]['remarks'];
                    $admin_warehouse_mapping->save();
                }
            }
        }
        DB::commit();

        return redirect()->route('admin.partner.index')->with('success', 'Partner Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Partner Updatedd Fail');
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
            $partner = Partner::findOrFail($id);

            $fileDeleted = commoncomponent::s3BucketFileDelete($partner->user_info->image, $partner->user_info->image_path);

            $partner->delete();
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
}
