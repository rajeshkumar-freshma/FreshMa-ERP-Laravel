<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Role Edit') || auth()->user()->can('Role View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Role Edit')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.role-management.roles.edit', $model->id) }}">
                            <i class="fa fa-pencil"></i> Edit</a>
                    </li>
                @endcan
                @can('Role View')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.role-management.roles.show', $model->id) }}">
                            <i class="fa fa-eye"></i> View</a>
                    </li>
                @endcan
                {{-- <li>
                <a class="dropdown-item" href="{{ route('admin.role-management.roles.destroy', $model->id) }}">
                    <i class="fa fa-eye"></i> Delete</a>
            </li> --}}
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="{{ route('admin.role-management.roles.destroy', $model->id) }}" data-destroy="{{ route('admin.role-management.roles.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
