<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Tax Rate Edit') || auth()->user()->can('Tax Rate View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Tax Rate Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.tax-rate.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Tax Rate View')
                    <li><a class="dropdown-item" href="{{ route('admin.tax-rate.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.tax-rate.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
