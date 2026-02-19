<x-default-layout>

    <!--begin::Invoice 2 main-->
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
                                {{ @$purchaseOrder->purchase_order_number }}
                            </div>
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
                                            {{ @$purchaseOrder->delivery_date ? date('d-M-Y', strtotime(@$purchaseOrder->delivery_date)) : '' }}</span>
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
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue To:
                                    </div>
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800"><b
                                            class="tm_f16 tm_primary_color">{{ @$purchaseOrder->user_details->first_name }}
                                            {{ @$purchaseOrder->user_details->last_name }}</b>
                                    </div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{ @$purchaseOrder->user_details->email }}<br>
                                        {{ @$purchaseOrder->user_details->phone_number }}
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
                                        Vadapalani,
                                        <br />Chennai
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">PURCHASE
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
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color ">
                                                    Qty</th>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color">
                                                    Price</th>
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
                                            @forelse (@$purchaseOrder->purchase_order_product_details as $key => $item)
                                                <tr class="{{ $key % 2 == 0 ? 'tm_gray_bg' : '' }}">
                                                    <td class="tm_width_2">{{ $key + 1 }}
                                                    </td>
                                                    <td class="tm_width_2">
                                                        {{ $item->product ? $item->product->name : 'no product' }}
                                                    </td>
                                                    <td class="tm_width_2">
                                                        {{ $item->given_quantity }}
                                                    </td>
                                                    <td class="tm_width_1">
                                                        {{ $item->per_unit_price }}
                                                    </td>
                                                    <td class="tm_width_2">
                                                        {{ $item->discount_type == 1 ? $item->discount_amount : $item->amount * ($item->discount_percentage / 100) }}
                                                    </td>
                                                    <td class="tm_width_2">
                                                        {{ $item->tax_details ? $item->tax_details->tax_name . '-' . $item->tax_details->tax_rate . '%' : '-' }}
                                                    </td>
                                                    <td class="tm_width_2 tm_text_right">
                                                        {{ $item->sub_total }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $totalAmount += $item->sub_total;
                                                    $totalTaxAmount += $item->tax_value;
                                                    $totalDiscountAmount +=
                                                        $item->discount_type == 1
                                                            ? $item->discount_amount
                                                            : $item->amount * ($item->discount_percentage / 100);
                                                    $total += $item->total;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="7">No purchase order
                                                        details available</td>
                                                </tr>
                                            @endforelse
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
                                                ₹{{ number_format(@$total ?? 0, 2, '.', ',') }}
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
                                @if (@$purchaseOrder->payment_status == $item['value'])
                                    <span
                                        class="badge badge-light-{{ $item['color'] }} me-2">{{ $item['name'] }}</span>
                                @endif
                            @endforeach
                        </div>
                        <!--end::Labels-->
                        <!--begin::Title-->
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">
                            TRANSACTIONS
                            DETAILS</h6>
                        <!--end::Title-->
                        <div class="flex-grow-1">
                            <!--begin::Table-->
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table mb-3">
                                    <thead>
                                        @forelse (@$purchaseOrder->paymentTransactions as $key => $item)
                                            <tr>
                                                <td>{{ $item->transaction_number }}<br>
                                                    @if ($item->payment_type_details)
                                                        <span
                                                            class="badge badge-light-primary me-2">{{ $item->payment_type_details->slug }}</span>
                                                    @endif
                                                    <span>
                                                        ({{ date('d-M-Y', strtotime($item->transaction_datetime)) }})
                                                    </span>
                                                </td>
                                                <td>₹{{ $item->amount }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No purchase order details
                                                    available</td>
                                            </tr>
                                        @endforelse
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--begin::Item-->

                        <!--end::Item-->
                        <!--end::Item-->
                    </div>
                </div>
                <div class="tm_invoice_btns ">
                    <a href="javascript:window.print()" class="tm_invoice_btn sm_color1">
                        <span class="sm_btn_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 500 500">
                                <path
                                    d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                                    fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32"
                                    fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24"
                                    fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                <circle cx="392" cy="184" r="24" fill='currentColor' />
                            </svg>
                        </span>
                        <span class="tm_btn_text">Print</span>
                    </a>
                    {{-- <a id="tm_download_btn" class="tm_invoice_btn tm_color2">
                        <span class="tm_btn_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                <path
                                    d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03"
                                    fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="32" />
                            </svg>
                        </span>
                        <span class="tm_btn_text">Download</span>
                    </a> --}}
                </div>
                <!--end::Item-->
            </div>

            <!--end::Invoice 2 sidebar-->
        </div>

        <!--end::Sidebar-->
    </div>
    <!--end::Layout-->

</x-default-layout>
