<!--begin::Action--->
@if (isset($imageData))
    @if ($imageData != null)
        <img src="{{ $imageData }}" style="width: 50px; height : 50px;">
    @else
        -
    @endif
@endif

@if (isset($model))
    <div class="btn-group">
        @if (auth()->user()->can('Stock Management Edit') || auth()->user()->can('Stock Management View'))
            <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Stock Management Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.stock-management.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.stock-management.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
@endif
<!--end::Action--->
