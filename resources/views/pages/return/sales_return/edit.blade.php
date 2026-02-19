<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Sales Return',
            'menu_1_link' => route('admin.sales-return.index'),
            'menu_2' => 'Edit Sales Return',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Sales Order Return'])
        <!--begin::Card header-->
    </div>
    <!--begin::Content-->
    <!--begin::Form-->
    <form id="sales_order_form" class="form" method="POST" action="{{ route('admin.sales-return.update', $sales_order_return->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="sales_order">
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                @php
                                $invoice_number = commoncomponent()->invoice_no('sales_order_return');
                                @endphp
                                <label for="sales_order_return_number" class="required form-label">{{ __('Sales Order Return ID') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="sales_order_return_number" name="sales_order_return_number" value="{{ old('sales_order_return_number', $sales_order_return->sales_order_return_number) }}" placeholder="PO Number" required readonly/>
                                @if ($errors->has('sales_order_return_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('sales_order_return_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="return_from" class="required form-label">{{ __('Return From') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="return_from" id="return_from" aria-label="{{ __('Select Return From') }}" data-control="select2" data-placeholder="{{ __('Select Return From..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Return From..') }}</option>
                                            <option value="1" {{ old('return_from', $sales_order_return->return_from) == 1 ? 'selected' : '' }}>{{ __('Store') }}</option>
                                            <option value="2" {{ old('return_from', $sales_order_return->return_from) == 2 ? 'selected' : '' }}>{{ __('Vendor') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('return_from'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('return_from') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4 store_div">
                            <div class="mb-5">
                                <label for="from_store_id" class="form-label required">{{ __('Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="from_store_id" id="from_store_id" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                            <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('from_store_id', $sales_order_return->from_store_id) ? 'selected' : '' }}> {{ $store->store_name . ' - ' . $store->store_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('from_store_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('from_store_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 sales_order_div">
                            <div class="mb-5">
                                <label for="sales_order_id" class="form-label">{{ __('Sales Order ID') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="sales_order_id" id="sales_order_id" aria-label="{{ __('Select Sales Order') }}" data-control="select2" data-placeholder="{{ __('Select Sales Order..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" data-search="on">
                                            <option value="">{{ __('Select Sales Order..') }}</option>
                                            @if ($sales_order)
                                            <option value="{{ $sales_order->id }}" selected>{{ __($sales_order->invoice_number) }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('sales_order_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('sales_order_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="return_date" class="required form-label">{{ __('Return Date') }}</label>
                                <div class="input-group">
                                    <input id="return_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="return_date" value="{{ old('return_date', $sales_order_return->return_date) }}" placeholder="Return Date" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('return_date'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('return_date') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_warehouse_id" class="form-label required">{{ __('Warehouse') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="to_warehouse_id" id="to_warehouse_id" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                            <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('to_warehouse_id', $sales_order_return->to_warehouse_id) ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('to_warehouse_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('to_warehouse_id') }}</strong>
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
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $sales_order_return->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                                <label for="ir_file" class="form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view($sales_order_return->image_full_url) !!}</label>
                                <input class="form-control form-control-sm" name="file" type="file" id="ir_file">
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--begin::Product Search-->
                    @include('pages.partials.product_search.index', ['loop_data' => old('products', $sales_order_return->order_details), 'main_data' => $sales_order_return, 'supplier' => $sales_order_return->supplier_id])
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
                                <label for="status" class=" form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks', $sales_order_return->remarks) }}</textarea>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Card body-->
            @if (old('transport_tracking.transport_type_id'))
            @include('pages.partials.transport_detail', ['transport_detail' => old('transport_tracking'), 'is_old_data' => 'old_value'])
            @php
            $tracking_count = count(old('transport_tracking'));
            @endphp
            @else
            @include('pages.partials.transport_detail', ['transport_detail' => $sales_order_return->transport_details, 'is_old_data' => 'edit_value'])
            @php
            $tracking_count = count($sales_order_return->transport_details);
            @endphp
            @endif

            @if (old('expense.expense_type_id'))
            @include('pages.partials.expense_detail', ['data' => old('expense'), 'is_old_data' => 'old_value'])
            @php
            $expense_count = count(old('expense'));
            @endphp
            @else
            @include('pages.partials.expense_detail', ['data' => $sales_order_return->expense_details, 'is_old_data' => 'edit_value'])
            @php
            $expense_count = count($sales_order_return->expense_details);
            @endphp
            @endif

            @if (old('payment_details.payment_type_id'))
            @include('pages.partials.payment_detail', ['payment_details' => old('payment_details'), 'is_old_data' => 'old_value'])
            @php
            $payment_count = count(old('payment_details'));
            @endphp
            @else
            @include('pages.partials.payment_detail', ['payment_details' => $indent_request->sales_return_transactions, 'is_old_data' => 'edit_value'])
            @php
            $payment_count = count($indent_request->sales_return_transactions);
            @endphp
            @endif

            <!--begin::Actions-->
            @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.sales-return.index')])
            <!--end::Actions-->
        </div>
        <!--end::Content-->
    </form>
    <!--end::Form-->
    </div>
    <!--end::Basic info-->

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

            $('#return_from').on('change', function() {
                var return_from = $(this).find(':selected').val();
                if (return_from == 1) {
                    $('.from_store_id').attr('required', true);
                    $('.sales_order_id').attr('required', false);
                    $('.store_div').show();
                    $('.sales_order_div').hide();
                } else {
                    $('.from_store_id').attr('required', false);
                    $('.sales_order_id').attr('required', true);
                    $('.store_div').hide();
                    $('.sales_order_div').show();
                }
            }).trigger('change');
        })
    </script>

    <script>
        $(function() {
            $("#sales_order_id").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: "Search Sales Order",
                ajax: {
                    url: "{{ route('admin.get_sales_order') }}",
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function(term) {
                        return {
                            term: term,
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        })
    </script>

    @include('pages.partials.date_picker_script')
    @include('pages.partials.common_script')
    @include('pages.partials.product_search.autocomplete')
    @include('pages.partials.product_search.calculation_script')
    @include('pages.partials.expense_add_more')
    @include('pages.partials.transport_detail_addmore')
    @include('pages.partials.payment_detail_addmore')
    @endsection
</x-default-layout>
