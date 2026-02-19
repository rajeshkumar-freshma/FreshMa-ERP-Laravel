<!--begin::Action--->
@if (isset($imageData))
    <td>
        @if ($imageData!=null)
            <img src="{{ $imageData }}" style="width: 50px; height : 50px;">
        @else
            -
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
                <li><a class="dropdown-item" href="{{ route('admin.store-sales.edit', $model->id) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.store-sales.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        </div>
    </td>
@endif
<!--end::Action--->
