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
            <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.staff-advance.edit', $model->id) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm" href="#" data-destroy="{{ route('admin.staff-advance.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        </div>

@endif
<!--end::Action--->
