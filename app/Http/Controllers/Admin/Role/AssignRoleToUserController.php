<?php

namespace App\Http\Controllers\Admin\Role;

use App\DataTables\Roles\AssignRoleToUserDataTable;
use App\DataTables\Roles\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CustomPermission;
use App\Models\PermissionGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;
use Log;

class AssignRoleToUserController extends Controller
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
    public function index(AssignRoleToUserDataTable $dataTable)
    {

        // // get collection
        // $roles->permissions;
        $data['users'] = Admin::with('roles')->get();
        // // return only the permission names:
        // $roles->permissions->pluck('name');
        // return view('admin.roles.index',compact('roles'));
        return $dataTable->render('pages.roles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $data['users'] = Admin::get();
            $data['roles'] = Role::with('permissions')->get();
            $permissiongroups = PermissionGroup::with('permissions')->get();
            // $permissions = $permissions->each(function ($e) {
            //     $e['children'] = CustomPermission::where('category', $e->category)
            //         ->orderBy('display_name', 'ASC')->get();
            // });

            return view('pages.assign_role_to_user.create', $data);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Assign Role To User Cannot be Created')->withInput();
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
        $this->validate($request, [
            'user_id' => 'required|integer',
            'role_ids' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $user = Admin::findOrFail($request->user_id);
            $role_ids = $request->role_ids;

            foreach ($role_ids as $role_id) {
                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
            }

            DB::commit();

            return redirect()->route('admin.role-management.assign-role-to-users.index')
                ->with('message', 'Roles assigned to user successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error assigning roles to user: ' . $e->getMessage())->withInput();
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
            return back()->with('error', 'Assign Role To User Cannot be Created')->withInput();
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
        $data['selectedUser'] = Admin::with('roles')->find($id);
        $data['users'] = Admin::get();

        // get the names of the user's roles
        // $roles = $data['selectedUser']->getRoleNames(); // Returns a collection
        $data['roles'] = Role::get();

        return view('pages.assign_role_to_user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'role_ids' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $user = Admin::find($request->user_id);
            $role_ids = $request->role_ids;
            $user->syncRoles([]);

            if (isset($role_ids) && is_array($role_ids)) {
                foreach ($role_ids as $role_id) {
                    $request->user_id;
                    $role = Role::findOrFail($role_id);
                    $user->syncRoles($role->name);
                }
            }
            DB::commit();

            return redirect()->route('admin.role-management.assign-role-to-users.index')
                ->with('message', 'Roles assigned to user successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error assigning roles to user: ' . $e->getMessage())->withInput();
        }
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
