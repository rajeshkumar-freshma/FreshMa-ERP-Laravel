<?php

namespace App\Http\Controllers\Admin\Role;

use App\DataTables\Roles\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Models\CustomPermission;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;
use Log;

class RoleController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware(['permission:role-list|role-create|role-edit|role-delete'], ['only' => ['index', 'store']]);
    //     $this->middleware(['permission:role-create'], ['only' => ['create', 'store']]);
    //     $this->middleware(['permission:role-edit'], ['only' => ['edit', 'update']]);
    //     $this->middleware(['permission:role-delete'], ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable)
    {
        $roles = Role::with('permissions')->get();
        // // get collection
        // $roles->permissions;

        // // return only the permission names:
        // $roles->permissions->pluck('name');
        // return view('admin.roles.index',compact('roles'));
        return $dataTable->render('pages.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $permissiongroups = PermissionGroup::with('permissions')->get();
            // $permissions = $permissions->each(function ($e) {
            //     $e['children'] = CustomPermission::where('category', $e->category)
            //         ->orderBy('display_name', 'ASC')->get();
            // });

            return view('pages.roles.create', compact('permissiongroups'));
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Role Cannot be Created')->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'role_name' => 'required|unique:roles,name',
            'permissions_ids' => 'nullable|array', // Assuming permissions_ids should be an array
        ]);
        DB::beginTransaction();
        try {
            $role = Role::Create(['name' => $validatedData['role_name']]);
            $permissions_ids = $request->permissions_ids;
            if (isset($permissions_ids) && is_array($permissions_ids)) {
                if (count($permissions_ids) > 0) {

                    $role->syncPermissions($validatedData['permissions_ids']);
                }
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();
            return redirect()->route('admin.role-management.roles.index')->with('message', 'Role created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Role cannot be created. Please try again.')->withInput();
        }
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
            $data['role'] = Role::with('permissions')->find($id);
            $data['permissionsGroup'] = PermissionGroup::with('permissions')->get();

            return view('pages.roles.show', $data);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Role Cannot be Created')->withInput();
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
        $data['role'] = Role::with('permissions')->find($id);
        $data['permissionsGroup'] = PermissionGroup::with('permissions')->get();

        return view('pages.roles.edit', $data);
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
        // Validate incoming request data
        $validatedData = $request->validate([
            'role_name' => 'required|string',
            'permissions_ids' => 'nullable|array', // Assuming permissions_ids should be an array
        ]);

        DB::beginTransaction();
        // try {
            // Find the role by ID or create a new one if not found
            $role = Role::updateOrCreate(['id' => $id], ['name' => $validatedData['role_name']]);

            // Sync permissions
            $permissions_ids = $validatedData['permissions_ids'] ?? [];
            $role->syncPermissions($permissions_ids);

            DB::commit();

            return redirect()->route('admin.role-management.roles.index')->with('message', 'Role updated successfully');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Role cannot be updated. Please try again.')->withInput();
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
            $role = Role::find($id);
            $role->delete();
            return to_route('admin.role-management.roles.index')->with('success', 'Roles Deleted Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Role Cannot be Created')->withInput();
        }
    }
}
