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
                <p>{{ @$transfer_data->from_warehouse->name . ' - ' . @$transfer_data->from_warehouse->code }}</p>
                <span class="badge bg-success text-white">Warehouse</span>
            @else
                <p>{{ @$transfer_data->from_store->store_name . ' - ' . @$transfer_data->from_store->store_code }}</p>
                <span class="badge bg-primary text-white">Store</span>
            @endif
        @endif
    </td>
@endif

@if (isset($model))
    <td class="no-wrap d-flex">
        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @if ($model->converted_to_distribution == 0)
                    <li><a class="dropdown-item" href="{{ route('admin.bulk-product-transfer.edit', $model->id) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                @endif
                <!-- <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.bulk-product-transfer.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        </div>
    </td>
@endif
<!--end::Action--->
