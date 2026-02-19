    <!DOCTYPE html>
    <html class="no-js" lang="en">

    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
        <!-- Site Title -->
        <title>Purchase Invoice</title>
        <link rel="stylesheet" href="{{ asset('assets/download/purchase/assets/css/style.css') }}">
    </head>

    <body>
        <div class="tm_container">
            <div class="tm_invoice_wrap">
                <div class="tm_invoice tm_style2 tm_type1 tm_accent_border" id="tm_download_section">
                    <div class="tm_invoice_in">
                        <div class="tm_invoice_head tm_top_head tm_mb20 tm_mb10_md">
                            <div class="tm_invoice_left">
                                {{-- <div class="tm_logo"><img src="assets/img/logo_white.svg" alt="Logo"></div> --}}
                            </div>
                            <div class="tm_invoice_right">
                                <div class="tm_grid_row tm_col_3">
                                    <div class="tm_text_center">
                                        <p class="tm_accent_color tm_mb0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 512 512" fill="currentColor">
                                                <path
                                                    d="M424 80H88a56.06 56.06 0 00-56 56v240a56.06 56.06 0 0056 56h336a56.06 56.06 0 0056-56V136a56.06 56.06 0 00-56-56zm-14.18 92.63l-144 112a16 16 0 01-19.64 0l-144-112a16 16 0 1119.64-25.26L256 251.73l134.18-104.36a16 16 0 0119.64 25.26z" />
                                            </svg>
                                        </p>
                                        {{ @$systemSetting->email ?? 'support@freshma.in' }}<br>
                                    </div>
                                    <div class="tm_text_center">
                                        <p class="tm_accent_color tm_mb0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 512 512" fill="currentColor">
                                                <path
                                                    d="M391 480c-19.52 0-46.94-7.06-88-30-49.93-28-88.55-53.85-138.21-103.38C116.91 298.77 93.61 267.79 61 208.45c-36.84-67-30.56-102.12-23.54-117.13C45.82 73.38 58.16 62.65 74.11 52a176.3 176.3 0 0128.64-15.2c1-.43 1.93-.84 2.76-1.21 4.95-2.23 12.45-5.6 21.95-2 6.34 2.38 12 7.25 20.86 16 18.17 17.92 43 57.83 52.16 77.43 6.15 13.21 10.22 21.93 10.23 31.71 0 11.45-5.76 20.28-12.75 29.81-1.31 1.79-2.61 3.5-3.87 5.16-7.61 10-9.28 12.89-8.18 18.05 2.23 10.37 18.86 41.24 46.19 68.51s57.31 42.85 67.72 45.07c5.38 1.15 8.33-.59 18.65-8.47 1.48-1.13 3-2.3 4.59-3.47 10.66-7.93 19.08-13.54 30.26-13.54h.06c9.73 0 18.06 4.22 31.86 11.18 18 9.08 59.11 33.59 77.14 51.78 8.77 8.84 13.66 14.48 16.05 20.81 3.6 9.53.21 17-2 22-.37.83-.78 1.74-1.21 2.75a176.49 176.49 0 01-15.29 28.58c-10.63 15.9-21.4 28.21-39.38 36.58A67.42 67.42 0 01391 480z" />
                                            </svg>
                                        </p>
                                        <br>
                                        Monday to Friday
                                    </div>
                                    <div class="tm_text_center">
                                        <p class="tm_accent_color tm_mb0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 512 512" fill="currentColor">
                                                <circle cx="256" cy="192" r="32" />
                                                <path
                                                    d="M256 32c-88.22 0-160 68.65-160 153 0 40.17 18.31 93.59 54.42 158.78 29 52.34 62.55 99.67 80 123.22a31.75 31.75 0 0051.22 0c17.42-23.55 51-70.88 80-123.22C397.69 278.61 416 225.19 416 185c0-84.35-71.78-153-160-153zm0 224a64 64 0 1164-64 64.07 64.07 0 01-64 64z" />
                                            </svg>
                                        </p>
                                        Vadapalani <br>
                                        --Chennai
                                    </div>
                                </div>
                            </div>
                            <div class="tm_shape_bg tm_accent_bg"></div>
                        </div>

                        <div class="tm_invoice_info tm_mb10">
                            <div class="tm_invoice_info_left">
                                <p class="tm_mb2"><b>Invoice To:</b></p>
                                <p>
                                    <b class="tm_f16 tm_primary_color">{{ @$purchaseOrder->user_details->first_name }}
                                        {{ @$purchaseOrder->user_details->last_name }}</b>
                                    <br>
                                    {{ @$purchaseOrder->user_details->email }}<br>
                                    {{ @$purchaseOrder->user_details->phone_number }}
                                </p>
                            </div>
                            <div class="tm_invoice_info_right">
                                <div
                                    class="tm_f50 tm_text_uppercase tm_text_center tm_invoice_title tm_mb15 tm_ternary_color tm_mobile_hide">
                                    Invoice</div>
                                <div class="tm_grid_row tm_col_3 tm_invoice_info_in tm_round_border tm_gray_bg">
                                    <div>
                                        <span>Invoice No:</span> <br>
                                        <b
                                            class="tm_f18 tm_accent_color">{{ @$payment_transactions_details->transaction_number }}</b>
                                    </div>
                                    <div>
                                        <span>Invoice Date:</span> <br>
                                        <b class="tm_f18 tm_accent_color">
                                            {{ \Carbon\Carbon::parse(@$payment_transactions_details->transaction_datetime)->format('d-m-Y') }}
                                        </b>
                                    </div>

                                    <div>
                                        <span>Grand Total:</span> <br>
                                        <b class="tm_f18 tm_accent_color">{{ @$totalAmount ?? 0 }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tm_table tm_style1">
                            <div class="tm_round_border">
                                <div class="tm_table_responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color">S.no</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color">Product Name</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color ">Qty</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color">Price</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color">Discount</th>
                                                <th class="tm_width_3 tm_semi_bold tm_primary_color">Tax</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color tm_text_right">Total
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
                                                {{-- <p>{{ $item->tax_details }}</p> --}}
                                                <tr class="{{ $key % 2 == 0 ? 'tm_gray_bg' : '' }}">
                                                    <td class="tm_width_2">{{ $key + 1 }}</td>
                                                    <td class="tm_width_2">
                                                        {{ $item->product ? $item->product->name : 'no product' }}</td>
                                                    <td class="tm_width_2">{{ $item->given_quantity }}</td>
                                                    <td class="tm_width_1">{{ $item->per_unit_price }}</td>
                                                    <td class="tm_width_2">
                                                        {{ $item->discount_type == 1 ? $item->discount_amount : $item->amount * ($item->discount_percentage / 100) }}
                                                    </td>
                                                    <td class="tm_width_2">
                                                        {{ $item->tax_details ? $item->tax_details->tax_name . '-' . $item->tax_details->tax_rate . '%' : 'no tax' }}
                                                    </td>
                                                    <td class="tm_width_2 tm_text_right">{{ $item->sub_total }}</td>
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
                                                    <td colspan="7">No purchase order details available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tm_invoice_footer tm_mb15 tm_m0_md">
                                <div class="tm_left_footer">
                                </div>
                                <div class="tm_right_footer">
                                    <table class="tm_mb15">
                                        <tbody>
                                            <tr>
                                                <td class="tm_width_3 tm_danger_color tm_border_none tm_pt0">Total
                                                    Discount</td>
                                                <td
                                                    class="tm_width_3 tm_danger_color tm_text_right tm_border_none tm_pt0">
                                                    ₹{{ number_format(@$totalDiscountAmount ?? 0, 2, '.', ',') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtotal
                                                </td>
                                                <td
                                                    class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                                                    ₹{{ number_format(@$totalAmount ?? 0, 2, '.', ',') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Total Tax
                                                </td>
                                                <td
                                                    class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                    ₹{{ number_format(@$totalTaxAmount, 2, '.', ',') }}</td>
                                            </tr>
                                            <tr>
                                                <td
                                                    class="tm_width_3 tm_border_top_0 tm_bold tm_f18 tm_white_color tm_accent_bg tm_radius_6_0_0_6">
                                                    Grand Total</td>
                                                <td
                                                    class="tm_width_3 tm_border_top_0 tm_bold tm_f18 tm_primary_color tm_text_right tm_white_color tm_accent_bg tm_radius_0_6_6_0">
                                                    ₹{{ number_format(@$totalAmount ?? 0, 2, '.', ',') }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div>
                                <b>Transactions Details:</b>
                            </div>
                            <div class="tm_round_border">
                                <div class="tm_table_responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color">S.no</th>
                                                <th class="tm_width_3 tm_semi_bold tm_primary_color">Transaction Type
                                                </th>
                                                <th class="tm_width_3 tm_semi_bold tm_primary_color">Payment Type
                                                </th>
                                                <th class="tm_width_5 tm_semi_bold tm_primary_color">Date</th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse (@$purchaseOrder->paymentTransactions as $key => $item)
                                                <tr>
                                                    <td>
                                                        <span>{{ $key + 1 }}</span>
                                                        {{-- <form
                                                            action="https://crm.codetez.com/invoice/124/9ca0e26727f3349b12aa345313241da9"
                                                            method="post" accept-charset="utf-8">
                                                            <input type="hidden" name="csrf_token_name"
                                                                value="a064353088949839a18e735d7fd1b012" />
                                                            <button type="submit"
                                                                value="{{ $payment_transactions_details->id }}"
                                                                class="btn btn-icon btn-default pull-right"
                                                                name="paymentpdf">
                                                                <i class="fa-regular fa-file-pdf"></i>
                                                            </button>
                                                        </form> --}}
                                                    </td>
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
                                                        <td class="tm_width_2">{{ $type->payment_type }}</td>
                                                        @else
                                                        <td class="tm_width_2">No Payment Type</td>
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
                                            <td colspan="7">No purchase order details available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="tm_invoice_footer tm_type1">
                                <div class="tm_left_footer"></div>
                                <div class="tm_right_footer">
                                    <div class="tm_sign tm_text_center">
                                        <img src="assets/img/sign.svg" alt="Sign">
                                        <p class="tm_m0 tm_ternary_color">Jhon Donate</p>
                                        <p class="tm_m0 tm_f16 tm_primary_color">Accounts Manager</p>
                                    </div>
                                </div>
                            </div> --}}
                </div>
                <div class="tm_bottom_invoice">
                    <div class="tm_bottom_invoice_left">
                        <p class="tm_primary_color tm_f12 tm_m0 tm_bold">Terms & Condition</p>
                        <p class="tm_m0 tm_f12">Invoice was created on a computer and is valid without the
                            signature and seal.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tm_invoice_btns tm_hide_print">
            <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                <span class="tm_btn_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <path
                            d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                            fill="none" stroke="currentColor" stroke-linejoin="round"
                            stroke-width="32" />
                        <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32"
                            fill="none" stroke="currentColor" stroke-linejoin="round"
                            stroke-width="32" />
                        <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24"
                            fill="none" stroke="currentColor" stroke-linejoin="round"
                            stroke-width="32" />
                        <circle cx="392" cy="184" r="24" fill='currentColor' />
                    </svg>
                </span>
                <span class="tm_btn_text">Print</span>
            </a>
            {{-- <button id="tm_download_btn" class="tm_invoice_btn tm_color2"
                >
                <span class="tm_btn_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <path
                            d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03"
                            fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="32" />
                    </svg>
                </span>
                <span class="tm_btn_text">Download</span>
            </button> --}}
        </div>
    </div>
</div>
<script src="{{ 'assets/download/purchase/assets/js/jquery.min.js' }}"></script>
<script src="{{ asset('assets/download/purchase/assets/js/jspdf.min.js') }}"></script>
<script src="{{ asset('assets/download/purchase/assets/js/html2canvas.min.js') }}"></script>
<script src="{{ asset('assets/download/purchase/assets/js/main.js') }}"></script>
</body>

</html>
