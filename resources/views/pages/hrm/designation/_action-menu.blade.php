<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Desigination Edit') || auth()->user()->can('Desigination View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Desigination Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.designation.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="{{ route('admin.designation.destroy', $model->id) }}" data-destroy="{{ route('admin.designation.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
