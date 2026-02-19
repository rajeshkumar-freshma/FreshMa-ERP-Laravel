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

@if (isset($model))
    <td class="no-wrap d-flex">
        <div class="btn-group">
            @if (auth()->user()->can('Purchase Order Edit') ||
                    auth()->user()->can('Purchase Order View') ||
                    auth()->user()->can('Purchase Order Product Pin Mapping Create'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    @can('Purchase Order Edit')
                        <li><a class="dropdown-item" href="{{ route('admin.purchase-order.edit', $model->id) }}"><i
                                    class="fa fa-pencil"></i> Edit</a></li>
                    @endcan
                    @can('Purchase Order View')
                        <li><a class="dropdown-item" href="{{ route('admin.purchase-order.show', $model->id) }}"><i
                                    class="fa fa-eye"></i> View</a></li>
                    @endcan
                    @can('Purchase Order Product Pin Mapping Create')
                        <li><a class="dropdown-item" href="{{ route('admin.product_pin_mapping', $model->id) }}"><i
                                    class="fa fa-pencil"></i> Product Pin Mapping</a></li>
                    @endcan
                    <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.purchase-order.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
