<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Payment Type Edit') || auth()->user()->can('Payment Type View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Payment Type Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.payment-type.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Payment Type View')
                    <li><a class="dropdown-item" href="{{ route('admin.payment-type.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.payment-type.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
