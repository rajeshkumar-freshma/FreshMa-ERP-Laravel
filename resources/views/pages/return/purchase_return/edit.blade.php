<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Purchase Return',
            'menu_1_link' => route('admin.purchase-return.index'),
            'menu_2' => 'Edit Purchase Return',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Purchase Order Return'])
        <!--begin::Card header-->
    </div>
    <!--begin::Content-->
    <!--begin::Form-->
    <form id="purchase_order_form" class="form" method="POST" action="{{ route('admin.purchase-return.update', $purchase_order_return->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="purchase_order_return">
            <!--begin::Form-->
            <!--begin::Card body-->
            <div class="card-body border-top px-9 py-4">
                <!--begin::Input group-->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="purchase_order_return_number" class="required form-label">{{ __('Purchase Order Return Number') }}</label>
                            <input type="text" class="form-control form-control-sm form-control-solid" name="purchase_order_return_number"  id="purchase_order_return_number" value="{{ old('purchase_order_return_number', $purchase_order_return->purchase_order_return_number) }}" placeholder="PO Number" readonly />
                            @if ($errors->has('purchase_order_return_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('purchase_order_return_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="purchase_order_id" class="form-label required">{{ __('Purchase Order ID') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="purchase_order_id" id="purchase_order_id" aria-label="{{ __('Select Purchase Order') }}" data-control="select2" data-placeholder="{{ __('Select Purchase Order..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" data-search="on">
                                        <option value="">{{ __('Select Purchase Order..') }}</option>
                                        @if ($purchase_order)
                                            <option value="{{ $purchase_order->id }}" selected>{{ __($purchase_order->purchase_order_number) }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('purchase_order_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('purchase_order_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="return_date" class="required form-label">{{ __('Return Date') }}</label>
                            <div class="input-group">
                                <input id="return_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="return_date" value="{{ old('return_date', $purchase_order_return->return_date) }}" placeholder="Return Date" required />
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
                            <label for="from_warehouse_id" class="form-label required">{{ __('Warehouse') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="from_warehouse_id" id="from_warehouse_id" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                        <option value="">{{ __('Select Warehouse..') }}</option>
                                        @foreach ($warehouses as $key => $warehouse)
                                            <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('from_warehouse_id', $purchase_order_return->from_warehouse_id) ? 'selected' : '' }}> {{ $warehouse->name }}</option>
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

                    <div class="col-md-4 store_div">
                        <div class="mb-5">
                            <label for="to_supplier_id" class="form-label required">{{ __('To Supplier') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="to_supplier_id" id="to_supplier_id" aria-label="{{ __('Select Supplier') }}" data-control="select2" data-placeholder="{{ __('Select Supplier..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Supplier..') }}</option>
                                        @foreach ($suppliers as $key => $supplier)
                                            <option data-kt-flag="{{ $supplier->id }}" value="{{ $supplier->id }}" {{ $supplier->id == old('to_supplier_id', $purchase_order_return->to_supplier_id) ? 'selected' : '' }}> {{ $supplier->name . ' - ' . $supplier->user_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('to_supplier_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('to_supplier_id') }}</strong>
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
                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $purchase_order_return->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                            <label for="ir_file" class="form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view($purchase_order_return->image_full_url) !!}</label>
                            <input class="form-control form-control-sm" name="file" type="file" id="ir_file">
                            </select>
                        </div>
                    </div>
                </div>

                <!--begin::Product Search-->
                @include('pages.partials.product_search.index', ['loop_data' => old('products', $purchase_order_return->purchase_order_return_details), 'main_data' => $purchase_order_return, 'supplier' => $purchase_order_return->supplier_id])
                <!--End::Product Search-->

                <!--begin::Product Search-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <div class="col-md-12">
                        <div class="mb-5">
                            <label for="status" class=" form-label">{{ __('Remarks') }}</label>

                            <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks', $purchase_order_return->remarks) }}</textarea>
                        </div>
                    </div>
                    <!--begin::Label-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->

            @if (old('transport_tracking.transport_type_id'))
                @include('pages.partials.transport_detail', ['transport_detail' => old('transport_tracking'), 'is_old_data' => 'old_value'])
                @php
                    $tracking_count = count(old('transport_tracking'));
                @endphp
            @else
                @include('pages.partials.transport_detail', ['transport_detail' => $purchase_order_return->transport_details, 'is_old_data' => 'edit_value'])
                @php
                    $tracking_count = count($purchase_order_return->transport_details);
                @endphp
            @endif

            @if (old('expense.expense_type_id'))
                @include('pages.partials.expense_detail', ['data' => old('expense'), 'is_old_data' => 'old_value'])
                @php
                    $expense_count = count(old('expense'));
                @endphp
            @else
                @include('pages.partials.expense_detail', ['data' => $purchase_order_return->expense_details, 'is_old_data' => 'edit_value'])
                @php
                    $expense_count = count($purchase_order_return->expense_details);
                @endphp
            @endif

            <!--begin::Actions-->
            @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.purchase-return.index')])

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
            })
        </script>

        <script>
            $(function() {
                $("#purchase_order_id").select2({
                    minimumInputLength: 3,
                    allowClear: true,
                    placeholder: "Search Purchase Order",
                    ajax: {
                        url: "{{ route('admin.get_purchase_order') }}",
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
