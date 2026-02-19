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
        <div class="btn-group">
            @if (auth()->user()->can('Cash Paind To Offiice Edit') || auth()->user()->can('Cash Paind To Offiice View'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    @can('Cash Paind To Offiice Edit')
                        <li><a class="dropdown-item" href="{{ route('admin.cash-paid-office.edit', $model->id) }}"><i
                                    class="fa fa-pencil"></i> Edit</a></li>
                    @endcan
                    @can('Cash Paind To Offiice View')
                        <li><a class="dropdown-item" href="{{ route('admin.cash-paid-office.show', $model->id) }}"><i
                                    class="fa fa-eye"></i> View</a></li>
                    @endcan
                    <!-- <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.cash-paid-office.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
