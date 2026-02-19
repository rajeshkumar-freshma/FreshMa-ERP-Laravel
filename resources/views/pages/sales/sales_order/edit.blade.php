<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Sales Order',
            'menu_1_link' => route('admin.sales-order.index'),
            'menu_2' => 'Edit Sales Order',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Sale'])
        <!--begin::Card header-->
    </div>

    <!--begin::Content-->
    <div id="item_details" class="collapse show">
        <!--begin::Form-->
        <form id="item_details_form" class="form" method="POST"
            action="{{ route('admin.sales-order.update', $sales_order->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!--begin::Card body-->
            <div class="card">
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="invoice_number"
                                    class="required form-label">{{ __('Invoice Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    id="invoice_number" name="invoice_number"
                                    value="{{ old('invoice_number', $sales_order->invoice_number) }}" placeholder="VSR-"
                                    readonly />
                                @if ($errors->has('invoice_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('invoice_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="text" name="route" id="route" value="{{ @$route }}" hidden>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="indent_request" class="form-label">{{ __('Order Indent Request') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="vendor_indent_request_id" id="indent_request"
                                            aria-label="{{ __('Select Vendor Indent Request') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Vendor Indent Request..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Vendor Indent Request..') }}</option>
                                            @foreach (@$vendor_indent_requests as $key => $vendor_indent_request)
                                                <option data-kt-flag="{{ $vendor_indent_request->id }}"
                                                    value="{{ @$vendor_indent_request->id }}"
                                                    {{ @$vendor_indent_request->id == old('vendor_indent_request_id', $sales_order->vendor_id) ? 'selected' : '' }}>
                                                    {{ @$vendor_indent_request->request_code . ' - ' . @$vendor_indent_request->vendor->first_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('vendor_indent_request_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('vendor_indent_request_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="delivered_date"
                                    class="required form-label">{{ __('Delivery Date') }}</label>
                                <div class="input-group">
                                    <input id="delivered_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="delivered_date"
                                        value="{{ old('delivered_date', $sales_order->delivered_date) }}" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('delivered_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('delivered_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_warehouse" class="form-label required">{{ __('Warehouse') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_id" id="ir_warehouse"
                                            aria-label="{{ __('Select Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Warehouse..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}"
                                                    value="{{ $warehouse->id }}"
                                                    {{ $warehouse->id == old('warehouse_id', $sales_order->warehouse_id) ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('warehouse_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('warehouse_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_store" class="form-label required">{{ __('Store') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="ir_store" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}"
                                                    {{ $store->id == old('store_id', $sales_order->store_id) ? 'selected' : '' }}>
                                                    {{ $store->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('store_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_vendor" class="form-label required">{{ __('Vendor') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="vendor_id" id="ir_vendor"
                                            aria-label="{{ __('Select Vendor') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Vendor..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Vendor..') }}</option>
                                            @foreach ($vendors as $key => $vendor)
                                                <option data-kt-flag="{{ $vendor->id }}"
                                                    data-vendor_percentage="{{ $vendor->vendor_detail != null ? $vendor->vendor_detail->vendor_commission : 0 }}"
                                                    data-editable="{{ $vendor->vendor_detail != null ? $vendor->vendor_detail->it_can_edit_on_billing : 0 }}"
                                                    value="{{ $vendor->id }}"
                                                    {{ $vendor->id == old('vendor_id', $sales_order->vendor_id) ? 'selected' : '' }}>
                                                    {{ $vendor->first_name . ' - ' . $vendor->last_name . ' - ' . $vendor->user_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('vendor_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('vendor_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class="required form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', $sales_order->status) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('status'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_file" class="form-label">{{ __('Attachments') }}</label>
                                <input class="form-control form-control-sm" name="file" type="file"
                                    id="ir_file">
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--begin::Product Search-->
                    @include('pages.partials.product_search.index', [
                        'loop_data' => old('products', $sales_order->product_details),
                        'main_data' => $sales_order,
                    ])
                    <!--End::Product Search-->

                    {{-- <!--begin::Product Items-->
                    @include('pages.indent_request.store.item', ['units' => $units, 'data' => old('products')])

                    @include('pages.indent_request.store.calculation_part')
                    <!--End::Product Items--> --}}

                    <!--begin::Product Search-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="remarks" class=" form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30"
                                    rows="3">{{ old('remarks') }}</textarea>
                            </div>
                            @if ($errors->has('remarks'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('remarks') }}</strong>
                            </span>
                        @endif
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Card body-->
            @if (old('transport_tracking.transport_type_id'))
                @include('pages.partials.transport_detail', [
                    'transport_detail' => old('transport_tracking'),
                    'is_old_data' => 'old_value',
                ])
                @php
                    $tracking_count = count(old('transport_tracking'));
                @endphp
            @else
                @include('pages.partials.transport_detail', [
                    'transport_detail' => $sales_order->transport_details,
                    'is_old_data' => 'edit_value',
                ])
                @php
                    $tracking_count = count($sales_order->transport_details);
                @endphp
            @endif

            @if (old('expense.expense_type_id'))
                @include('pages.partials.expense_detail', [
                    'data' => old('expense'),
                    'is_old_data' => 'old_value',
                ])
                @php
                    $expense_count = count(old('expense'));
                @endphp
            @elseif(count($sales_order->expense_details) > 0)
                @include('pages.partials.expense_detail', [
                    'data' => $sales_order->expense_details,
                    'is_old_data' => 'edit_value',
                ])
                @php
                    $expense_count = count($sales_order->expense_details);
                @endphp
            @else
                @include('pages.partials.expense_detail', ['data' => [], 'is_old_data' => 'default'])
                @php
                    $expense_count = 1;
                @endphp
            @endif

            @if (old('payment_details.payment_type_id'))
                @include('pages.partials.payment_detail', [
                    'payment_details' => old('payment_details'),
                    'is_old_data' => 'old_value',
                ])
                @php
                    $payment_count = count(old('payment_details', []));
                @endphp
            @else
                @include('pages.partials.payment_detail', [
                    'payment_details' => $indent_request->sales_order_transactions,
                    'is_old_data' => 'edit_value',
                ])
                @php
                    $payment_count = count($indent_request->sales_order_transactions);
                @endphp
            @endif
            <!--begin::Actions-->
            @include('pages.partials.form_footer', [
                'is_save' => false,
                'back_url' => route('admin.sales-order.index'),
            ])
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
    <!--end::Basic info-->

    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>
            $(function() {
                $(".fsh_flat_datepicker").flatpickr();

                $('.product_checkbox').on('click', function() {
                    var product = $(this).attr('data-id');
                    var unit_id = $(this).attr('data-unit_id');
                    var quantity = $(this).attr('data-quantity');
                    if ($(this)[0].checked == true) {
                        getProductDetails(product, unit_id, quantity);
                    } else {
                        $('.current_row' + product).remove();
                    }
                })

                $('#po_item').on('change', function() {
                    var product = $(this).find(':selected').text();
                    $.ajax({
                        type: 'get',
                        url: "{{ route('admin.purchaseitem.render') }}",
                        data: {
                            product: product
                        },
                        success: function(res) {
                            $('.appendData').append(res)
                        }
                    })
                })
            })
        </script>
        @include('pages.partials.date_picker_script')
        @include('pages.partials.expense_add_more')
        @include('pages.partials.common_script')
        @include('pages.partials.product_search.calculation_script')
        @include('pages.partials.product_search.autocomplete')
        @include('pages.partials.transport_detail_addmore')
        @include('pages.partials.payment_detail_addmore')
    @endsection
</x-default-layout>
