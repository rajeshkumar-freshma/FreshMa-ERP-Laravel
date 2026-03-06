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
        @if (auth()->user()->can('Apply Loan Edit') || auth()->user()->can('Apply Loan View'))
            <button type="button" class="btn btn-primary btn-sm btn-xs dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Apply Loan Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.loans.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Apply Loan View')
                    <li><a class="dropdown-item" href="{{ route('admin.loans.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li>
                @endcan
            </ul>
        @endif
    </div>
@endif
<!--end::Action--->
