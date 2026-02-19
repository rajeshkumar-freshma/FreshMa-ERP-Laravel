<!--begin::Action--->
<td class="no-wrap d-flex">
    <div class="btn-group">
        @if (auth()->user()->can('Income/Expense Category Edit') || auth()->user()->can('Income/Expense Category View'))
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                @can('Income/Expense Category Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.income-expense-type.edit', $model->id) }}"><i
                                class="fa fa-pencil"></i> Edit</a></li>
                @endcan
                @can('Income/Expense Category Edit')
                    <li><a class="dropdown-item" href="{{ route('admin.income-expense-type.show', $model->id) }}"><i
                                class="fa fa-eye"></i> View</a></li>
                @endcan
                <!-- <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item btn btn-sm btn-danger" href="#" data-destroy="{{ route('admin.income-expense-type.destroy', $model->id) }}"><i class="fa fa-trash"></i> Delete</a></li> -->
            </ul>
        @endif
    </div>
</td>
<!--end::Action--->
