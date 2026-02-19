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
            @if (auth()->user()->can('Profit And Loss Report View'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    @can('Profit And Loss Report View')
                        <li><a class="dropdown-item"
                                href="{{ route('admin.dailysalesreportview', $model->delivered_date) }}"><i
                                    class="fa fa-eye"></i> View</a></li>
                    @endcan
                    {{-- <li><a class="dropdown-item" href="{{ route('admin.daily-sales-report.edit', $model->id) }}"><i class="fa fa-pencil"></i> Edit</a></li> --}}
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
