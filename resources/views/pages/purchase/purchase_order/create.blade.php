<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Purchase Order',
            'menu_1_link' => route('admin.purchase.index'),
            'menu_2' => 'Add Purchase Order',
        ])
    @endsection
    <!--begin::Basic info-->
    <form id="warehouse_indent_request_form" class="form" method="POST"
        action="{{ route('admin.purchase-order.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Purchase Order'])
            <!--begin::Card header-->

            <!--begin::Content-->
            <div id="warehouse_indent_request">
                <!--begin::Form-->
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                @php
                                    $invoice_number = commoncomponent()->invoice_no('purchase_order');
                                @endphp
                                <label for="purchase_order_number"
                                    class="required form-label">{{ __('Purchase Order Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    id="purchase_order_number" name="purchase_order_number"
                                    value="{{ old('purchase_order_number', $invoice_number) }}" placeholder="PO Number"
                                    required />
                                @if ($errors->has('purchase_order_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('purchase_order_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="warehouse_ir_id" class="form-label">{{ __('Warehouse Request ID') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_ir_id" id="warehouse_ir_id"
                                            aria-label="{{ __('Select Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Warehouse..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouse_indent_requests as $key => $warehouse_indent_request)
                                                <option data-kt-flag="{{ $warehouse_indent_request->id }}"
                                                    value="{{ $warehouse_indent_request->id }}"
                                                    {{ $warehouse_indent_request->id == old('warehouse_ir_id') ? 'selected' : '' }}>
                                                    {{ $warehouse_indent_request->request_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('warehouse_ir_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('warehouse_ir_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="delivery_date"
                                    class="required form-label">{{ __('Delivery Date') }}</label>
                                <div class="input-group">
                                    <input id="delivery_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="delivery_date" value="{{ old('delivery_date') }}" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('delivery_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('delivery_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_warehouse" class="form-label required">{{ __('Warehouse') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_id" id="ir_warehouse"
                                            aria-label="{{ __('Select Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Warehouse..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}"
                                                    value="{{ $warehouse->id }}"
                                                    {{ $warehouse->id == old('warehouse_id') ? 'selected' : '' }}>
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

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class="required form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
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

                        <!--begin::Label-->
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
                    @include('pages.partials.product_search.index', ['loop_data' => old('products')])
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
                <!--end::Card body-->
            </div>
            <!--end::Content-->
        </div>
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
                'transport_detail' => [],
                'is_old_data' => 'edit_value',
            ])
            @php
                $tracking_count = count([]);
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
        @elseif(isset($indent_request->expense_details) && count($indent_request->expense_details) > 0)
            @include('pages.partials.expense_detail', [
                'data' => $indent_request->expense_details,
                'is_old_data' => 'edit_value',
            ])
            @php
                $expense_count = count($indent_request->expense_details);
            @endphp
        @else
            @include('pages.partials.expense_detail', ['data' => [], 'is_old_data' => 'default'])
            @php
                $expense_count = 1;
            @endphp
        @endif

        {{-- <div class="pin_details" id="pin_details" style="display: none">

            @if (old('product_pin_details.payment_type_id'))
                @include('pages.purchase.pin_mapping.product_pin_mapping', [
                    'product_pin_details' => old('product_pin_details'),
                    'is_old_data' => 'old_value',
                ])
                @php
                    $product_pin = count(old('product_pin'));
                @endphp
            @else
                @include('pages.purchase.pin_mapping.product_pin_mapping', [
                    'product_pin_details' => [],
                    'is_old_data' => 'edit_value',
                ])
                @php
                    $product_pin = count([]);
                @endphp
            @endif
        </div> --}}

        @if (old('payment_details.payment_type_id'))
            @include('pages.partials.payment_detail', [
                'payment_details' => old('payment_details'),
                'is_old_data' => 'old_value',
            ])
            @php
                $payment_count = count(old('payment', [])); // Provide an empty array as default value
            @endphp
        @else
            @include('pages.partials.payment_detail', [
                'payment_details' => [],
                'is_old_data' => 'edit_value',
            ])
            @php
                $payment_count = count([]); // No need to count an empty array
            @endphp
        @endif


        <!--begin::Actions-->
        @include('pages.partials.form_footer', [
            'is_save' => true,
            'back_url' => route('admin.purchase-order.index'),
        ])
        <!--end::Actions-->
    </form>

    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>
            $(function() {
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


            });
        </script>
        <script>
            $(function() {
                $(document).ready(function() {
                    var product_id = $('#products').val();
                    console.log("product_id");
                    console.log(product_id);
                    $('#stock_verified').on('change', function() {
                        var stock_value = $(this).val();
                        console.log("stock_value");
                        console.log(stock_value);
                        // $('#box_no').block();
                        // $('#box_view').block();
                        if (stock_value == 1) {
                            console.log("stock_value is 1");
                            $('#pin_details').show();
                        }
                    })
                })
            });
        </script>
        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Check if any of the specified errors exist
                if ({{ $errors->has('expense.expense_amount.') ? 'true' : 'false' }} ||
                    {{ $errors->has('payment_details.transaction_amount.') ? 'true' : 'false' }}) {
                    // Find the tab link corresponding to the tab you want to open
                    var tabLink = $('a[href="#collapse"]');
                    // Trigger a click event on the tab link
                    tabLink.each(function() {
                        $(this).click();
                    });
                    // Output to console
                    console.log("hi am manikandan");
                    // Remove 'collapse' class from the divs
                    $('#expense_details').removeClass('collapse');
                    $('#payment_details').removeClass('collapse');
                } else {
                    console.log("nothing to comes on here");
                }
            });
        </script> --}}

        @include('pages.partials.date_picker_script')
        @include('pages.partials.common_script')
        @include('pages.partials.product_search.autocomplete')
        @include('pages.partials.product_search.calculation_script')
        @include('pages.partials.expense_add_more')
        @include('pages.partials.transport_detail_addmore')
        @include('pages.partials.payment_detail_addmore')
        {{-- @include('pages.purchase.pin_mapping.product_pin_mapping_addmore') --}}
    @endsection
</x-default-layout>
