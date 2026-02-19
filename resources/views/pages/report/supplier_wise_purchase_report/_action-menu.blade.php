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
            @if (auth()->user()->can('Supplier Wise Purchase Report  Download') ||
                    auth()->user()->can('Supplier Wise Purchase Report  View'))
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    {{-- <li><a class="dropdown-item" href="{{ route('admin.daily-sales-report.edit', $model->id) }}"><i class="fa fa-pencil"></i> Edit</a></li> --}}
                    <li>
                        <form action="{{ route('admin.supplier_wise_purchase_view') }}" method="GET">
                            @csrf
                            <input type="hidden" name="supplier_id" value="{{ $model->id }}">
                            <button type="submit" class="dropdown-item">
                                <i class="fa fa-eye"></i> Purchase Orders View
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('admin.supplier_payment_transactions') }}" method="GET">
                            @csrf
                            <input type="hidden" name="supplier_id" value="{{ $model->id }}">
                            <button type="submit" class="dropdown-item">
                                <i class="fa fa-eye"></i> Payment Transactions View
                            </button>
                        </form>
                        {{-- <a class="dropdown-item"
                        href="{{ route('admin.supplier_wise_purchase_orders', $model->delivered_date) }}"><i
                            class="fa fa-eye"></i> Payment Transactions View</a></li> --}}
                </ul>
            @endif
        </div>
    </td>
@endif
<!--end::Action--->
