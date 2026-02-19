<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Holiday Edit') || auth()->user()->can('Holiday View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Holiday Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.holiday.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="{{ route('admin.holiday.destroy', $model->id) }}" data-destroy="{{ route('admin.holiday.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
