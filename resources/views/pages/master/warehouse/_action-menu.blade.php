<!--begin::Action--->
@if (isset($imageData))
    @if ($imageData != null)
        <img src="{{ $imageData }}" style="width: 50px; height : 50px;">
    @else
        -
    @endif
@endif


@if (isset($model))
    <!-- Example single danger button -->
    <div class="btn-group">
        @if (auth()->user()->can('Warehouse Edit') || auth()->user()->can('Warehouse View'))
            <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Warehouse Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.warehouse.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Warehouse View')
                    <li><a class="dropdown-item" target="_blank"
                            href="{{ route('admin.warehouse.show', $model->id) }}"><i class="fa fa-eye"></i> View</a>
                    </li>
                @endcan
                <!-- <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.warehouse.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
@endif
<!--end::Action--->

