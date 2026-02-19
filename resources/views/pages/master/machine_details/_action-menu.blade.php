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

@if (isset($online_status))
    <td>
        @if ($online_status->Online == 1)
            <span class="badge bg-success">Online</span>
        @else
            <span class="badge bg-danger">Offline</span>
        @endif
    </td>
@endif

@if (isset($model))
    <td class="no-wrap d-flex">
        <div class="btn-group">
            @if (auth()->user()->can('Supplier Edit') || auth()->user()->can('Supplier View'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.machine-details.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.machine-details.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li>
                    <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.machine-details.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
