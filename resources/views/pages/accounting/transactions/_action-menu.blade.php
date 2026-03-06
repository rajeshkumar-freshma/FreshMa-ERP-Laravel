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
        <li><a class="dropdown-item" href="{{ route('admin.transaction.edit', $model->id) }}"><i class="fa fa-pencil"></i> Edit</a></li>
    </ul>
</div>
@endif
<!--end::Action--->
