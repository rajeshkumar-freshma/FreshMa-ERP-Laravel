<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Transport Type Edit') || auth()->user()->can('Transport Type View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Transport Type Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.transport-type.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Transport Type View')
                    <li><a class="dropdown-item" href="{{ route('admin.transport-type.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.transport-type.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
