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
        @if (auth()->user()->can('Accounts Edit') || auth()->user()->can('Accounts View'))
            <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Accounts Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.accounts.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
            </ul>
        @endif
    </div>
@endif
<!--end::Action--->
