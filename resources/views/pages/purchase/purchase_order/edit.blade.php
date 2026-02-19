    <x-default-layout>
        <!--begin::Card-->
        @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Purchase Order',
            'menu_1_link' => route('admin.purchase.index'),
            'menu_2' => 'Edit Purchase Order',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Purchase Order'])
        <!--begin::Card header-->
    </div>
    <!--begin::Content-->
    <!--begin::Form-->
    <form id="warehouse_indent_request_form" class="form" method="POST" action="{{ route('admin.purchase-order.update', $indent_request->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="warehouse_indent_request">
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="purchase_order_number" class="required form-label">{{ __('Purchase Order Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="purchase_order_number" name="purchase_order_number" value="{{ old('purchase_order_number', $indent_request->purchase_order_number) }}" placeholder="WIR-" required />
                                @if ($errors->has('purchase_order_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('purchase_order_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="text" name="route" id="route" value="{{ @$route }}" hidden>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="warehouse_ir_id" class="form-label">{{ __('Warehouse Request ID') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_ir_id" id="warehouse_ir_id" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouse_indent_requests as $key => $warehouse_indent_request)
                                                <option data-kt-flag="{{ $warehouse_indent_request->id }}" value="{{ $warehouse_indent_request->id }}" {{ $warehouse_indent_request->id == old('warehouse_ir_id', $indent_request->warehouse_ir_id) ? 'selected' : '' }}> {{ $warehouse_indent_request->request_code }}</option>
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
                                <label for="delivery_date" class="required form-label">{{ __('Delivery Date') }}</label>
                                <div class="input-group">
                                    <input id="delivery_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="delivery_date" value="{{ old('delivery_date', $indent_request->delivery_date) }}" required />
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
                                <label for="ir_warehouse" class="form-label">{{ __('Warehouse') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_id" id="ir_warehouse" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('warehouse_id', $indent_request->warehouse_id) ? 'selected' : '' }}> {{ $warehouse->name }}</option>
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
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $indent_request->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                                <label for="ir_file" class="form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view($indent_request->image_full_url) !!}</label>
                                <input class="form-control form-control-sm" name="file" type="file" id="ir_file">
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--begin::Product Search-->
                    @include('pages.partials.product_search.index', ['loop_data' => old('products', $indent_request->purchase_order_product_details), 'main_data' => $indent_request, 'supplier' => $indent_request->supplier_id, 'supplier' => $indent_request->box_number,'box_'])
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

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks', $indent_request->remarks) }}</textarea>
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
                @include('pages.partials.transport_detail', ['transport_detail' => old('transport_tracking'), 'is_old_data' => 'old_value'])
                @php
                    $tracking_count = count(old('transport_tracking'));
                @endphp
            @else
                @include('pages.partials.transport_detail', ['transport_detail' => $indent_request->transport_details, 'is_old_data' => 'edit_value'])
                @php
                    $tracking_count = count($indent_request->transport_details);
                @endphp
            @endif

            @if (old('expense.expense_type_id'))
                @include('pages.partials.expense_detail', ['data' => old('expense'), 'is_old_data' => 'old_value'])
                @php
                    $expense_count = count(old('expense'));
                @endphp
            @else
                @include('pages.partials.expense_detail', ['data' => $indent_request->expense_details, 'is_old_data' => 'edit_value'])
                @php
                    $expense_count = count($indent_request->expense_details);
                @endphp
            @endif

            @if (old('payment_details.payment_type_id'))
                @include('pages.partials.payment_detail', ['payment_details' => old('payment_details'), 'is_old_data' => 'old_value'])
                @php
                    $payment_count = count(old('payment_details'));
                @endphp
            @else
                @include('pages.partials.payment_detail', ['payment_details' => $indent_request->purchase_order_transactions, 'is_old_data' => 'edit_value'])
                @php
                    $payment_count = count($indent_request->purchase_order_transactions);
                @endphp
            @endif

            <!--begin::Actions-->
            @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.purchase-order.index')])
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
