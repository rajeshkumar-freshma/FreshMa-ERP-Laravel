<?php

namespace App\Http\Livewire\Permission;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionModal extends Component
{
    public $name;
    public $guard_name;

    public Permission $permission;

    protected $rules = [
        'name' => 'required|string',
        'guard_name' => 'required|string',
    ];

    // This is the list of listeners that this component listens to.
    protected $listeners = [
        'modal.show.permission_name' => 'mountPermission',
        'modal.show.permission_guard_name' => 'mountPermission',
        'delete_permission' => 'delete'
    ];

    public function render()
    {
        return view('livewire.permission.permission-modal');
    }

    public function mountPermission($permission_name = '', $permission_guard_name = '')
    {
        if (empty($permission_name)) {
            // Create new
            $this->permission = new Permission;
            $this->name = '';
            $this->guard_name = '';
            return;
        }

        // Get the role by name.
        $permission = Permission::where([['name', $permission_name], ['guard_name', $permission_guard_name]])->first();
        if (is_null($permission)) {
            $this->emit('error', 'The selected permission [' . $permission_name . '] is not found');
            return;
        }

        $this->permission = $permission;

        // Set the name and checked permissions properties to the role's values.
        $this->name = $this->permission->name;
        $this->guard_name = $this->permission->guard_name;
    }

    public function submit()
    {
        $this->validate();

        $this->permission->name = strtolower($this->name);
        $this->permission->guard_name = $this->guard_name;
        if ($this->permission->isDirty()) {
            $this->permission->save();
        }

        // Emit a success event with a message indicating that the permissions have been updated.
        $this->emit('success', 'Permission updated');
    }

    public function delete($name)
    {
        $permission = Permission::where('name', $name)->first();

        if (!is_null($permission)) {
            $permission->delete();
        }

        $this->emit('success', 'Permission deleted');
    }
}
