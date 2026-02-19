<x-default-layout>
    <!--begin::Navbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.return.sales_return.sales_return_nav')
        </div>
        <!--end::Navbar-->
        <!--begin::Col-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Table Widget 5-->
            <div class="card card-flush h-xl-100">
                <!--begin::Card header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Transport Report Details</span>
                    </h3>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-end pe-0 min-w-100px">Transaction Number</th>
                                <th class="text-end pe-0 min-w-100px">Payment Type</th>
                                <th class="text-end pe-0 min-w-100px">Transaction Date</th>
                                <th class="text-end pe-0 min-w-150px">Amount</th>
                                <th class="text-end pe-0 min-w-150px">Status</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach (@$sales_return_payments as $sales_payment)
                            <tr>
                                <!--begin::Product ID-->
                                <td class="text-end">{{ $sales_payment->transaction_number ?? '-' }}</td>
                                <td class="text-end">{{ $sales_payment->type_name ?? '-' }}</td>
                                <!--begin::Date added-->
                                <td class="text-end">{{ $sales_payment->transaction_datetime ?? '-' }}</td>
                                <!--end::Date added-->
                                <td class="text-end">{{ $sales_payment->amount ?? '-' }}</td>
                                <td class="text-end">@include('pages.partials.statuslabel', ['payment_status' => $sales_payment->status])</td>
                                <!--end::Qty-->
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($sales_return_payments) && count($sales_return_payments) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ @$sales_return_payments->firstItem() }} to {{ @$sales_return_payments->lastItem() }} of
                            {{ @$sales_return_payments->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ @$sales_return_payments->withQueryString()->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    @endif
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Table Widget 5-->
        </div>
        <!--end::Col-->
    </div>
</x-default-layout>
