<x-default-layout>
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Vendor Sale'])
        <!--begin::Card header-->
    </div>

    <!--begin::Content-->
    <div id="item_details" class="collapse show">
        <!--begin::Form-->
        <form id="item_details_form" class="form" method="POST" action="{{ route('admin.customer-sales.update', $vendor_sale->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!--begin::Card body-->
            <div class="card">
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="vendor_sales_number" class="required form-label">{{ __('Vendor Sale Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="vendor_sales_number" name="vendor_sales_number" value="{{ old('vendor_sales_number', $vendor_sale->vendor_sales_number) }}" placeholder="VSR-" required />
                                @if ($errors->has('vendor_sales_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('vendor_sales_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="indent_request" class="form-label">{{ __('Vendor Indent Request') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="vendor_indent_request_id" id="indent_request" aria-label="{{ __('Select Vendor Indent Request') }}" data-control="select2" data-placeholder="{{ __('Select Vendor Indent Request..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Vendor Indent Request..') }}</option>
                                            @foreach ($vendor_indent_requests as $key => $vendor_indent_request)
                                                <option data-kt-flag="{{ $vendor_indent_request->id }}" value="{{ $vendor_indent_request->id }}" {{ $vendor_indent_request->id == old('vendor_indent_request_id', $vendor_sale->vir_id) ? 'selected' : '' }}> {{ $vendor_indent_request->request_code . ' - ' . $vendor_indent_request->vendor->first_name }}</option>
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
                                <label for="delivered_date" class="required form-label">{{ __('Delivery Date') }}</label>
                                <div class="input-group">
                                    <input id="delivered_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="delivered_date" value="{{ old('delivered_date', $vendor_sale->delivered_date) }}" required />
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
                                <label for="ir_vendor" class="form-label">{{ __('Vendor') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="vendor_id" id="ir_vendor" aria-label="{{ __('Select Vendor') }}" data-control="select2" data-placeholder="{{ __('Select Vendor..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Vendor..') }}</option>
                                            @foreach ($vendors as $key => $vendor)
                                                <option data-kt-flag="{{ $vendor->id }}" data-vendor_percentage="{{ $vendor->vendor_detail!=null ? $vendor->vendor_detail->vendor_commission : 0 }}" data-editable="{{ $vendor->vendor_detail!=null ? $vendor->vendor_detail->it_can_edit_on_billing : 0 }}" value="{{ $vendor->id }}" {{ $vendor->id == old('vendor_id', $vendor_sale->vendor_id) ? 'selected' : '' }}> {{ $vendor->first_name . ' - ' . $vendor->last_name . ' - ' . $vendor->user_code }}</option>
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
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $vendor_sale->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                                <input class="form-control form-control-sm" name="file" type="file" id="ir_file">
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--begin::Product Search-->
                    @include('pages.partials.product_search.index', ['loop_data' => old('products', $vendor_sale->product_details), 'main_data' => $vendor_sale])
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
                                <label for="status" class="required form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks') }}</textarea>
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
                @include('pages.partials.transport_detail', ['transport_detail' => $vendor_sale->transport_details, 'is_old_data' => 'edit_value'])
                @php
                    $tracking_count = count($vendor_sale->transport_details);
                @endphp
            @endif

            @if (old('expense.expense_type_id'))
                @include('pages.partials.expense_detail', ['data' => old('expense'), 'is_old_data' => 'old_value'])
                @php
                    $expense_count = count(old('expense'));
                @endphp
            @elseif(count($vendor_sale->expense_details)>0)
                @include('pages.partials.expense_detail', ['data' => $vendor_sale->expense_details, 'is_old_data' => 'edit_value'])
                @php
                    $expense_count = count($vendor_sale->expense_details);
                @endphp
            @else
                @include('pages.partials.expense_detail', ['data' => [], 'is_old_data' => 'default'])
                @php
                    $expense_count = 1;
                @endphp
            @endif

            <!--begin::Actions-->
            @include('pages.partials.form_footer', ['is_save' => false, 'back_url' => route('admin.customer-sales.index')])
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
    @endsection
</x-default-layout>
