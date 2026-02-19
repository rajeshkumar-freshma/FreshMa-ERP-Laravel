<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Denomination Edit') || auth()->user()->can('Denomination View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Denomination Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.denomination-type.edit', $model->id) }}"><i class="fa fa-pencil"></i>
                            Edit</a></li>
            @endif
            {{-- @can('Unit View')
                <li><a class="dropdown-item" target="_blank" href="{{ route('admin.unit.show', $model->id) }}"><i
                            class="fa fa-eye"></i> View</a></li>
            @endcan --}}
            </ul>
            @endif
        </div>
    </td>
    <!--end::Action--->
