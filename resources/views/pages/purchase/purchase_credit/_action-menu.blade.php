<!--begin::Action--->
@if (isset($imageData))
    @if ($imageData!=null)
        <img src="{{ $imageData }}" style="width: 50px; height : 50px;">
    @else
        -
    @endif
@endif

@if (isset($model))
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.purchase-order.show', $model->id) }}"><i
                class="fa fa-eye"></i> View</a></li>
        </ul>
    </div>
@endif
<!--end::Action--->
