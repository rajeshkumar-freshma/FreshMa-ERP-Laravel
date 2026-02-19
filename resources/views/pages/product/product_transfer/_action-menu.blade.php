<!--begin::Action--->
@if (isset($imageData))
    <td>
        @if ($imageData != null)
            <img src="{{ $imageData }}" style="width: 50px; height : 50px;">
        @else
            -
        @endif
    </td>
@endif

<!--begin::Action--->
@if (isset($transfer_data))
    <td>
        @if ($type == 'transfer_from')
            @if ($transfer_data->transfer_from == 1)
                <p>{{ @$transfer_data->from_warehouse->name . ' - ' . @$transfer_data->from_warehouse->code }}</p>
                <span class="badge bg-success text-white">Warehouse</span>
            @else
                <p>{{ @$transfer_data->from_store->store_name . ' - ' . @$transfer_data->from_store->store_code }}</p>
                <span class="badge bg-primary text-white">Store</span>
            @endif
        @else
            @if ($transfer_data->transfer_to == 1)
                <p>{{ @$transfer_data->to_warehouse->name . ' - ' . @$transfer_data->to_warehouse->code }}</p>
                <span class="badge bg-success text-white">Warehouse</span>
            @else
                <p>{{ @$transfer_data->to_store->store_name . ' - ' . @$transfer_data->to_store->store_code }}</p>
                <span class="badge bg-primary text-white">Store</span>
            @endif
        @endif
    </td>
@endif

@if (isset($model))
    <td class="no-wrap d-flex">
        <div class="btn-group">
            @if (auth()->user()->can('Product Transfer Edit') || auth()->user()->can('Product Transfer View'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    @can('Product Transfer Edit')
                        @if ($model->status != 10 && isset($model->status))
                            <li><a class="dropdown-item" href="{{ route('admin.product-transfer.edit', $model->id) }}"><i
                                        class="fa fa-pencil"></i> Edit</a></li>
                        @endif
                    @endcan
                    @can('Product Transfer View')
                        <li><a class="dropdown-item" href="{{ route('admin.product-transfer.show', $model->id) }}"><i
                                    class="fa fa-eye"></i> View</a></li>
                    @endcan
                    <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.product-transfer.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
