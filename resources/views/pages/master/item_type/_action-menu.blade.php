<!--begin::Action--->
<td class="no-wrap d-flex">
    <!-- Example single danger button -->
    <div class="btn-group">
        @if (auth()->user()->can('Item Type Edit') || auth()->user()->can('Item Type View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Item Type Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.item-type.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Item Type View')
                    {{-- <li><a class="dropdown-item" href="{{ route('admin.item-type.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li> --}}
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.item-type.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
