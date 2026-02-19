<x-default-layout>
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-20">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                    <!--begin::Invoice 2 content-->
                    <div class="mt-n1">
                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800 mb-8">Invoice:
                                {{ @$salesOrder->invoice_number }}</div>
                            <!--end::Label-->
                            <!--begin::Row-->
                            <div class="row g-5 mb-11">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue Date:
                                    </div>
                                    <!--end::Label-->
                                    <!--end::Col-->
                                    <div class="fw-bold fs-6 text-gray-800">
                                        <?= date('d-M-Y', strtotime($payment_transactions_details->transaction_datetime) ?? '') ?>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Delivered
                                        Date:</div>
                                    <!--end::Label-->

                                    <!--end::Info-->
                                    <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2">
                                            {{ @$salesOrder->delivered_date ? date('d-M-Y', strtotime($salesOrder->delivered_date)) : '' }}</span>
                                        </span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row g-5 mb-12">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued To:
                                    </div>
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800"><b
                                            class="tm_f16 tm_primary_color">{{ @$salesOrder->user_details->first_name }}
                                            {{ @$salesOrder->user_details->last_name }}</b>
                                    </div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{ @$salesOrder->user_details->email }}<br>
                                        {{ @$salesOrder->user_details->phone_number }}
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:
                                    </div>
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">
                                        <b>RRK FRESHMA</b>
                                    </div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        Vadapalani
                                        <br />Chennai
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">SALES
                                ORDERS
                                DETAILS</h6>
                            <!--begin::Content-->
                            <div class="flex-grow-1">
                                <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
                                    <table class="table mb-3">
                                        <thead>
                                            <tr>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color">
                                                    S.no</th>
                                                <th class="tm_width_3 tm_semi_bold tm_primary_color">
                                                    Product Name</th>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color">
                                                    Price</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color ">
                                                    Qty</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color">
                                                    Discount</th>
                                                <th class="tm_width_3 tm_semi_bold tm_primary_color">
                                                    Tax</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color tm_text_right">
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        @php
                                            $totalAmount = 0;
                                            $totalDiscountAmount = 0;
                                            $totalTaxAmount = 0;
                                            $total = 0;
                                        @endphp
                                        <tbody>
                                            @if (@$salesOrder->product_details != null)

                                                @forelse (@$salesOrder->product_details as $key => $item)
                                                    <tr class="{{ $key % 2 == 0 ? 'tm_gray_bg' : '' }}">
                                                        <td class="tm_width_2">{{ $key + 1 }}
                                                        </td>
                                                        <td class="tm_width_2">
                                                            {{ $item->product ? $item->product->name : 'no product' }}
                                                        </td>
                                                        <td class="tm_width_1">
                                                            {{ $item->per_unit_price }}</td>
                                                        <td class="tm_width_2">
                                                            {{ $item->given_quantity }}</td>
                                                        <td class="tm_width_2">
                                                            {{ $item->discount_type == 1 ? $item->discount_amount : $item->amount * ($item->discount_percentage / 100) }}
                                                        </td>
                                                        <td class="tm_width_2">
                                                            {{ $item->tax_details ? $item->tax_details->tax_name . '-' . $item->tax_details->tax_rate . '%' : '-' }}
                                                        </td>
                                                        <td class="tm_width_2 tm_text_right">
                                                            {{ $item->sub_total }}</td>
                                                    </tr>
                                                    @php
                                                        $totalAmount += $item->sub_total;
                                                        $totalTaxAmount += $item->tax_value;
                                                        $totalDiscountAmount += $item->discount_type == 1 ? $item->discount_amount : $item->amount * ($item->discount_percentage / 100);
                                                        $total += $item->total;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="7">No purchase order details available</td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <p>No datas:</p>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                                <!--begin::Container-->
                                <div class="d-flex justify-content-end">
                                    <!--begin::Section-->
                                    <div class="mw-300px">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                                Discount Amount</div>
                                            <!--end::Accountname-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">
                                                ₹{{ number_format(@$totalDiscountAmount ?? 0, 2, '.', ',') }}
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                                Subtotal:</div>
                                            <!--end::Accountname-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">
                                                ₹{{ number_format(@$totalAmount ?? 0, 2, '.', ',') }}
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountnumber-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                                Total Tax</div>
                                            <!--end::Accountnumber-->
                                            <!--begin::Number-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">
                                                ₹{{ number_format(@$totalTaxAmount, 2, '.', ',') }}
                                            </div>
                                            <!--end::Number-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Code-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                                Grand Total</div>
                                            <!--end::Code-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">
                                                ₹{{ number_format(@$total ?? 0, 2, '.', ',') }}
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Invoice 2 content-->
                </div>
                <!--end::Content-->
                <!--begin::Sidebar-->
                <div class="m-0">
                    <!--begin::Invoice 2 sidebar-->
                    <div
                        class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                        <!--begin::Title-->
                        <!--begin::Labels-->
                        <div class="mb-8">
                            @foreach (config('app.payment_status') as $item)
                                @if (@$salesOrder->payment_status == $item['value'])
                                    <span
                                        class="badge badge-light-{{ $item['color'] }} me-2">{{ $item['name'] }}</span>
                                @endif
                            @endforeach
                        </div>
                        <!--end::Labels-->
                        <!--begin::Title-->
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">
                            TRANSACTIONS DETAILS</h6>
                        <!--end::Title-->
                        <!--begin::Item-->
                        <div class="flex-grow-1">
                            <!--begin::Table-->
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table mb-3">
                                    <thead>
                                        @if (@$salesOrder->sales_order_transactions != null)
                                            @forelse (@$salesOrder->sales_order_transactions as $key => $item)
                                                <tr>
                                                    <td>{{ $item->transaction_number }}<br>
                                                        @if ($item->payment_type_details)
                                                            <span
                                                                class="badge badge-light-primary me-2">{{ $item->payment_type_details->payment_type }}</span>
                                                        @endif
                                                        <span>
                                                            ({{ date('d-M-Y', strtotime($item->transaction_datetime)) }})
                                                        </span>
                                                    </td>
                                                    <td>₹{{ $item->amount }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">No purchase order details available</td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <p>No Datas:</p>
                                        @endif
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--end::Item-->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Item-->
    </div>
    <!--end::Invoice 2 sidebar-->

</x-default-layout>
