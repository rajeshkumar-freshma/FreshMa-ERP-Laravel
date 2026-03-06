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
        @if (auth()->user()->can('Store Indent Request Edit') || auth()->user()->can('Store Indent Request View'))
            <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Store Indent Request Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.store-indent-request.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
            </ul>
        @endif
    </div>
@endif
<!--end::Action--->
