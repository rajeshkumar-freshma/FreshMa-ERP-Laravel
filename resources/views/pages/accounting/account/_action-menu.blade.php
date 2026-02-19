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

@if (isset($model))
    <td class="no-wrap d-flex">
        <!-- Example single danger button -->
        <div class="btn-group">
            @if (auth()->user()->can('Accounts Edit') || auth()->user()->can('Accounts View'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    @can('Accounts Edit')
                        <li><a class="dropdown-item" href="{{ route('admin.accounts.edit', $model->id) }}"><i
                                    class="fa fa-pencil"></i> Edit</a></li>
                    @endcan
                    <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm" href="#" data-destroy="{{ route('admin.accounts.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
