    <!DOCTYPE html>
    <html class="no-js" lang="en">

    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
        <!-- Site Title -->
        <title>Sales Invoice</title>
        <link rel="stylesheet" href="{{ asset('assets/download/sales/assets/css/style.css') }}">
    </head>

    <body>
        <div class="cs-container">
            <div class="cs-invoice cs-style1">
                <div class="cs-invoice_in" id="download_section">
                    <div class="cs-invoice_head cs-type1 cs-mb25 column border-bottom-none">
                        <div class="cs-invoice_right cs-text_left">
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16  display-flex justify-content-flex-start">
                                <p class="cs-primary_color"><b>Invocie:</b> {{ @$salesOrder->user_details->first_name ?? 'No User Name' }}
                                </p>
                            </div>
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16  display-flex justify-content-flex-start">
                                <p class="cs-primary_color cs-mb0"><b>City:</b></p>
                                <p class="cs-mb0">
                                    Chennai
                                </p>
                            </div>
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16  display-flex justify-content-flex-start">
                                <p class="cs-primary_color cs-mb0"><b>State:</b></p>
                                <p class="cs-mb0">Tamil Nadu</p>
                            </div>
                        </div>
                        <div class="cs-invoice_right cs-text_right">
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16  display-flex justify-content-flex-end">
                                <p class="cs-primary_color"><b>Total:</b></p>
                                {{-- <p class="cs-mb0">₹</p> --}}
                            </div>
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16  display-flex justify-content-flex-end">
                                <p class="cs-primary_color cs-mb0"><b>Invoice Date:</b></p>
                                <p class="cs-mb0">
                                    <?= date('d-m-Y', strtotime(@$payment_transactions_details->transaction_datetime) ?? '') ?>
                                </p>
                            </div>
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16  display-flex justify-content-flex-end">
                                <p class="cs-primary_color cs-mb0"><b>Invoice No:</b></p>
                                <p class="cs-mb0">{{ @$salesOrder->invoice_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="display-flex cs-text_center">
                        <div class="cs-border-1"></div>
                        <h5 class="cs-width_12 cs-dip_green_color">SALES INVOICE</h5>
                        <div class="cs-border-1"></div>
                    </div>

                    <div class="cs-invoice_head cs-mb7 ">
                        <div class="cs-invoice_left cs-mr97">
                            <b class="cs-primary_color">Vendor Name:</b>
                            <p class="cs-mb8">{{ @$salesOrder->user_details->first_name }}</p>
                            <p><b class="cs-primary_color cs-semi_bold">Vendor GSTIN:</b> <br>{{ @$salesOrder->user_details->gst_number }}</p>
                        </div>
                        <div class="cs-invoice_right">
                            <b class="cs-primary_color">Billing Address:</b>
                            <p>
                                RRK FRESHMA, <br /> Vadapalani,<br />Chennai <br />
                                Tamil Nadu
                            </p>
                        </div>
                        <div class="cs-invoice_center">
                            <b class="cs-primary_color">Shipping Address:</b>
                            <p>
                                {{ @$salesOrder->user_details->first_name }}
                                {{ @$salesOrder->user_details->last_name }},
                                <br />{{ @$salesOrder->user_details->email }},<br />{{ @$salesOrder->user_details->phone_number }}
                                <br />
                            </p>
                        </div>
                    </div>
                    <div class="cs-border"></div>
                    <ul class="cs-grid_row cs-col_3 cs-mb0 cs-mt20">
                        <li>
                            <p class="cs-mb20"><b class="cs-primary_color">Status:</b> <span class="cs-primary_color">
                                    @foreach (config('app.purchase_status') as $item)
                                        @if (@$salesOrder->status == $item['value'])
                                            <span
                                                class="badge badge-light-{{ $item['color'] }} me-2">{{ $item['name'] }}</span>
                                        @endif
                                    @endforeach
                                </span></p>
                        </li>
                        <li>
                            <p class="cs-mb20"><b class="cs-primary_color">Payment Status:</b> <span
                                    class="cs-primary_color">
                                    @foreach (config('app.payment_status') as $item)
                                        @if (@$salesOrder->payment_status == $item['value'])
                                            <span
                                                class="badge badge-light-{{ $item['color'] }} me-2">{{ $item['name'] }}</span>
                                        @endif
                                    @endforeach
                                </span></p>
                        </li>
                        <li>
                            <p class="cs-mb20"><b class="cs-primary_color">Delivered Date:</b> <span
                                    class="cs-primary_color">{{ date('d-m-Y', strtotime(@$salesOrder->delivered_date) ?? '') }}</span>
                            </p>
                        </li>
                    </ul>
                    <div class="cs-border cs-mb30"></div>
                    <div class="cs-table cs-style2 cs-f12">
                        <div class="cs-round_border">
                            <div class="cs-table_responsive">
                                <table>
                                    <thead>
                                        <tr class="cs-focus_bg">
                                            <th class="cs-width_2 cs-semi_bold cs-primary_color">S.no</th>
                                            <th class="cs-width_2 cs-semi_bold cs-primary_color">Item</th>
                                            <th class="cs-width_2 cs-semi_bold cs-primary_color">Quantity</th>
                                            <th class="cs-width_2 cs-semi_bold cs-primary_color">Tax</th>
                                            <th class="cs-width_2 cs-semi_bold cs-primary_color">Discount</th>
                                            <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">Total
                                            </th>
                                        </tr>
                                    </thead>
                                    @php
                                        $subTotal = 0;
                                        $totalDiscountAmount = 0;
                                        $totalTax = 0;
                                        $total = 0;
                                    @endphp
                                    <tbody>
                                        @if (@$salesOrder->product_details != null)
                                            @forelse (@$salesOrder->product_details as $key=>$item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ @$item->product->name }}</td>
                                                    <td>{{ @$item->given_quantity }}</td>
                                                    <td>{{ @$item->tax_rate->tax_name }}-{{ @$item->tax_rate->tax_rate }}%
                                                    </td>
                                                    @if (@$item->discount_type == 1)
                                                        <td>{{ @$item->discount_amount }}</td>
                                                    @else
                                                        <td>{{ @$item->amount * (@$item->discount_percentage / 100) }}
                                                        </td>
                                                    @endif
                                                    <td class="cs-text_right cs-primary_color">{{ @$item->total }}</td>
                                                    @php
                                                        $subTotal += @$item->sub_total;
                                                        $totalDiscountAmount +=
                                                            @$item->discount_type == 1
                                                                ? @$item->discount_amount
                                                                : @$item->amount * (@$item->discount_percentage / 100);
                                                        $totalTax += @$item->tax_value;
                                                        $total += @$item->total;
                                                    @endphp
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">No Sales order details available</td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <p>No Datas</p>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="cs-table cs-style2 cs-mt20">
                        <div class="cs-table_responsive">
                            <table>
                                <tbody>
                                    <tr class="cs-table_baseline">
                                        <td class="cs-width_3 cs-text_right">
                                            <p class="cs-primary_color cs-bold cs-f16 cs-mb5 ">Total Discount:</p>
                                            <p class="cs-primary_color cs-bold cs-f16 cs-mb5 ">Total Tax:</p>
                                            <p class="cs-mb5 cs-mb5 cs-f15 cs-primary_color cs-semi_bold">Sub Total:</p>
                                            <p class="cs-border border-none"></p>
                                            <p class="cs-primary_color cs-bold cs-f16 cs-mb5 ">Total:</p>
                                        </td>
                                        <td class="cs-width_3 cs-text_rightcs-f16">
                                            <p class="cs-primary_color cs-bold cs-f16 cs-mb5 cs-text_right">
                                                ₹{{ $totalDiscountAmount }}</p>
                                            <p class="cs-primary_color cs-bold cs-f16 cs-mb5 cs-text_right">
                                                ₹{{ $totalTax }}</p>
                                            <p class="cs-mb5 cs-mb5 cs-text_right cs-f15 cs-primary_color cs-semi_bold">
                                                ₹{{ $subTotal }}
                                            </p>
                                            <p class="cs-border"></p>
                                            <p class="cs-primary_color cs-bold cs-f16 cs-mb5 cs-text_right">
                                                ₹{{ $total }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div>
                    <hr> <br>
                </div>
                <div>
                    <p class="cs-mb20"><b class="cs-primary_color">TRANSACTIONS DETAILS:</b>
                </div>
                <div class="cs-table cs-style2 cs-f12">
                    <div class="cs-round_bohrrder">
                        <div class="cs-table_responsive">
                            <table>
                                <thead>
                                    <tr class="cs-focus_bg">
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">S.no</th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Transaction Type</th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Payment Method</th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Date</th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (@$salesOrder->sales_order_transactions != null)
                                        @forelse (@$salesOrder->sales_order_transactions as $key=>$item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                @foreach (config('app.payment_transactions_type') as $key => $type)
                                                    @if ($type['value'] == @$item->transaction_type)
                                                        <td class="tm_width_2">
                                                            {{ $type['name'] }}
                                                        </td>
                                                    @break
                                                @endif
                                            @endforeach
                                            @foreach ($payment_types as $key => $type)
                                                @if ($type->id == @$item->payment_type_id)
                                                    <td class="tm_width_2">
                                                        {{ $type->payment_type }}
                                                    </td>
                                                @break
                                            @endif
                                        @endforeach
                                        <td class="tm_width_2">
                                            <?= date('d-m-Y', strtotime(@$item->transaction_datetime) ?? '') ?>
                                        </td>

                                        <td class="tm_width_2 tm_text_right">
                                            {{ $item->amount }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No Sales order details available</td>
                                    </tr>
                                @endforelse
                            @else
                                <p>No Datas:</p>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="cs-invoice_btns cs-hide_print">
            <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                    <path
                        d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                        fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                    <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32"
                        fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                    <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                        stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                    <circle cx="392" cy="184" r="24" />
                </svg>
                <span>Print</span>
            </a>
            {{-- <button id="download_btn" class="cs-invoice_btn cs-color2">
                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                    <title>Download</title>
                    <path
                        d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="32" />
                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="32" d="M176 272l80 80 80-80M256 48v288" />
                </svg>
                <span>Download</span>
            </button> --}}
        </div>
    </div>
</div>
<script src="{{ 'assets/download/sales/assets/js/jquery.min.js' }}"></script>
<script src="{{ asset('assets/download/sales/assets/js/jspdf.min.js') }}"></script>
<script src="{{ asset('assets/download/sales/assets/js/html2canvas.min.js') }}"></script>
<script src="{{ asset('assets/download/sales/assets/js/main.js') }}"></script>
</body>

</html>
