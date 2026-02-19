<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Product Transfer',
            'menu_1_link' => route('admin.product-transfer.index'),
            'menu_2' => 'Add Product Transfer',
        ])
    @endsection
    <!--begin::Basic info-->
    <form id="product_transfer_form" class="form" method="POST" action="{{ route('admin.product-transfer.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Product Transfer'])
            <!--begin::Card header-->

            <!--begin::Content-->
            <div id="product_transfer">
                <!--begin::Form-->
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                @php
                                    $invoice_number = commoncomponent()->invoice_no('redistribution');
                                @endphp
                                <label for="transfer_order_number" class="required form-label">{{ __('Product Transfer Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="transfer_order_number" name="transfer_order_number" value="{{ old('transfer_order_number', $invoice_number) }}" placeholder="PO Number" required />
                                @if ($errors->has('transfer_order_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_order_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transfer_from" class="required form-label">{{ __('Transfer From') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transfer_from" id="transfer_from" aria-label="{{ __('Select Transfer From') }}" data-control="select2" data-placeholder="{{ __('Select Transfer From..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Transfer From..') }}</option>
                                            <option value="1" {{ old('transfer_from', 1) == 1 ? 'selected' : '' }}>{{ __('Warehouse') }}</option>
                                            <option value="2" {{ old('transfer_from') == 2 ? 'selected' : '' }}>{{ __('Store') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('transfer_from'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_from') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transfer_to" class="required form-label">{{ __('Transfer To') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transfer_to" id="transfer_to" aria-label="{{ __('Select Transfer To') }}" data-control="select2" data-placeholder="{{ __('Select Transfer To..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Transfer To..') }}</option>
                                            <option value="1" {{ old('transfer_to', 1) == 1 ? 'selected' : '' }}>{{ __('Warehouse') }}</option>
                                            <option value="2" {{ old('transfer_to') == 2 ? 'selected' : '' }}>{{ __('Store') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('transfer_to'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_to') }}</strong>
                                    </span>
                                @endif
                                @if(session('error'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ session('error') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 from_warehouse_div">
                            <div class="mb-5">
                                <label for="from_warehouse_id" class="form-label">{{ __('From Warehouse') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="from_warehouse_id" id="from_warehouse_id" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('from_warehouse_id') ? 'selected' : '' }}> {{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('from_warehouse_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_warehouse_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 from_store_div">
                            <div class="mb-5">
                                <label for="from_store_id" class="form-label">{{ __('From Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="from_store_id" id="from_store_id" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('from_store_id') ? 'selected' : '' }}> {{ $store->store_name . ' - ' . $store->store_code }}</option>
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

                        <div class="col-md-4 to_warehouse_div">
                            <div class="mb-5">
                                <label for="to_warehouse_id" class="form-label">{{ __('To Warehouse') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="to_warehouse_id" id="to_warehouse_id" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('to_warehouse_id') ? 'selected' : '' }}> {{ $warehouse->name }}</option>
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

                        <div class="col-md-4 to_store_div">
                            <div class="mb-5">
                                <label for="to_store_id" class="form-label">{{ __('To Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="to_store_id" id="to_store_id" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('to_store_id') ? 'selected' : '' }}> {{ $store->store_name . ' - ' . $store->store_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('to_store_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_store_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 sales_order_div">
                            <div class="mb-5">
                                <label for="store_indent_request_id" class="form-label">{{ __('Store Indent Request') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_indent_request_id" id="store_indent_request_id" aria-label="{{ __('Search Store Indent Request') }}" data-control="select2" data-placeholder="{{ __('Search Store Indent Request..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" data-search="on">
                                            <option value="">{{ __('Select Sales Order..') }}</option>
                                            @foreach (@$store_indent_requests as $key => $store_indent_request)
                                            <option data-kt-flag="{{ $store_indent_request->id }}"
                                                value="{{ $store_indent_request->id }}"
                                                {{ $store_indent_request->id == old('store_indent_request_id') ? 'selected' : '' }}>
                                                {{ $store_indent_request->request_code . ' - ' . $store_indent_request->store->store_name }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('store_indent_request_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_indent_request_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transfer_created_date" class="required form-label">{{ __('Transfer Date') }}</label>
                                <div class="input-group">
                                    <input id="transfer_created_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="transfer_created_date" value="{{ old('transfer_created_date') }}" placeholder="Return Date" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('transfer_created_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_created_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transfer_received_date" class="required form-label">{{ __('Receive Date') }}</label>
                                <div class="input-group">
                                    <input id="transfer_received_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="transfer_received_date" value="{{ old('transfer_received_date') }}" placeholder="Return Date" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('transfer_received_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_received_date') }}</strong>
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
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                                <input class="form-control form-control-sm" name="file" type="file" id="ir_file">
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
                                <label for="status" class="form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks') }}</textarea>
                            </div>
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
            @include('pages.partials.transport_detail', ['transport_detail' => old('transport_tracking'), 'is_old_data' => 'old_value'])
            @php
                $tracking_count = count(old('transport_tracking'));
            @endphp
        @else
            @include('pages.partials.transport_detail', ['transport_detail' => [], 'is_old_data' => 'edit_value'])
            @php
                $tracking_count = count([]);
            @endphp
        @endif

        @if (old('expense.expense_type_id'))
            @include('pages.partials.expense_detail', ['data' => old('expense'), 'is_old_data' => 'old_value'])
            @php
                $expense_count = count(old('expense'));
            @endphp
        @elseif(isset($indent_request->expense_details) && count($indent_request->expense_details) > 0)
            @include('pages.partials.expense_detail', ['data' => $indent_request->expense_details, 'is_old_data' => 'edit_value'])
            @php
                $expense_count = count($indent_request->expense_details);
            @endphp
        @else
            @include('pages.partials.expense_detail', ['data' => [], 'is_old_data' => 'default'])
            @php
                $expense_count = 1;
            @endphp
        @endif

        @if (old('payment_details.payment_type_id'))
            @include('pages.partials.payment_detail', ['payment_details' => old('payment_details'), 'is_old_data' => 'old_value'])
            @php
                $payment_count = count(old('payment'));
            @endphp
        @else
            @include('pages.partials.payment_detail', ['payment_details' => [], 'is_old_data' => 'edit_value'])
            @php
                $payment_count = count([]);
            @endphp
        @endif
        <!--begin::Actions-->
        @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.product-transfer.index')])
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

                $('#transfer_from').on('change', function() {
                    var transfer_from = $(this).find(':selected').val();
                    if (transfer_from == 1) {
                        $('.from_warehouse_id').attr('required', true);
                        $('.from_store_id').attr('required', false);
                        $('.from_store_id').val('');
                        $('.from_warehouse_div').show();
                        $('.from_store_div').hide();
                    } else {
                        $('.from_warehouse_id').attr('required', false);
                        $('.from_warehouse_id').val('');
                        $('.from_store_id').attr('required', true);
                        $('.from_warehouse_div').hide();
                        $('.from_store_div').show();
                    }
                }).trigger('change');

                $('#transfer_to').on('change', function() {
                    var transfer_to = $(this).find(':selected').val();
                    if (transfer_to == 1) {
                        $('.to_warehouse_id').attr('required', true);
                        $('.to_store_id').attr('required', false);
                        $('.to_store_id').val('');
                        $('.to_warehouse_div').show();
                        $('.to_store_div').hide();
                    } else {
                        $('.to_warehouse_id').attr('required', false);
                        $('.to_warehouse_id').val('');
                        $('.to_store_id').attr('required', true);
                        $('.to_warehouse_div').hide();
                        $('.to_store_div').show();
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
                                // _token: '{{ csrf_token() }}'
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
    @endsection
</x-default-layout>
