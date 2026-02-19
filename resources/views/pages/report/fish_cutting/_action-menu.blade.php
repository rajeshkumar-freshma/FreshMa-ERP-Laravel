<!--begin::Action-->
<td class="no-wrap d-flex">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            @if (@$model->transaction_type == 1)
                {{-- <a class="dropdown-item" href="{{ route('purchase-invoice', ['id' => $model->id]) }}">
                    <i class="fas fa-money-bill"></i>
                    View User Invoice
                </a> --}}
                <a class="dropdown-item" href="{{ route('admin.purchase-invoice1', ['id' => $model->id]) }}">
                    <i class="fas fa-money-bill"></i>
                    View Invoice
                </a>
                <li><a class="dropdown-item" href="{{ route('admin.purchase-receipt', $model->id) }}"><i
                            class="fas fa-receipt"></i>
                        Receipt</a></li>
            @else
                <a class="dropdown-item" href="{{ route('sales-invoice', ['id' => $model->id]) }}"><i
                        class="fas fa-money-bill"></i>
                    View Sales User Invoice
                </a>
                <a class="dropdown-item" href="{{ route('admin.sales-invoice1', ['id' => $model->id]) }}">
                    <i class="fas fa-money-bill"></i>
                    View Sales Invoice-1
                </a>
            @endif


        </ul>
    </div>
</td>
<!--end::Action-->
