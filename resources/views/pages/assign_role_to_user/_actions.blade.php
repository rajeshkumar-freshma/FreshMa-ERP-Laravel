<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Assign Role To User Edit') || auth()->user()->can('Assign Role To User View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Assign Role To User Edit')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('admin.role-management.assign-role-to-users.edit', $user->id) }}">
                            <i class="fa fa-pencil"></i> Edit</a>
                    </li>
                @endcan
                {{-- <li>
                <a class="dropdown-item" href="{{ route('admin.role-management.roles.destroy', $user->id) }}">
                    <i class="fa fa:trash-o"></i> Delete</a>
            </li> --}}
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="{{ route('admin.role-management.roles.destroy', $user->id) }}" data-destroy="{{ route('admin.role-management.roles.destroy', $user->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
