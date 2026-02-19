<x-default-layout>
   <!--begin::Card-->
   @section('_toolbar')
   @include('pages.partials.common-toolbar', [
       'title' => 'Edit Store Indent Request',
       'menu_1_link' => route('admin.store-indent-request.index'),
       'menu_2' => 'Edit Store Indent Request',
   ])
@endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Indent Request'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details" class="collapse show">
            <!--begin::Form-->
            <form id="item_details_form" class="form" method="POST"
                action="{{ route('admin.store-indent-request.update', $store_indent_request->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_code"
                                    class="required form-label">{{ __('Indent Request Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    id="ir_code" name="ir_code"
                                    value="{{ old('ir_code', $store_indent_request->request_code) }}" placeholder="SIR-"
                                    required />
                                @if ($errors->has('ir_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ir_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_request_date"
                                    class="required form-label">{{ __('Request Date') }}</label>
                                <div class="input-group">
                                    <input id="ir_request_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="request_date"
                                        value="{{ old('request_date', $store_indent_request->request_date) }}"
                                        required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('request_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('request_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_expected_date"
                                    class="required form-label">{{ __('Expected Date') }}</label>
                                <div class="input-group">
                                    <input id="ir_expected_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="expected_date"
                                        value="{{ old('expected_date', $store_indent_request->expected_date) }}"
                                        required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('expected_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expected_date') }}</strong>
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
                                                    {{ $warehouse->id == old('warehouse_id', $store_indent_request->warehouse_id) ? 'selected' : '' }}>
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
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}"
                                                    {{ $store->id == old('store_id', $store_indent_request->store_id) ? 'selected' : '' }}>
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

                        <!--begin::Label-->
                        {{-- <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class="required form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $store_indent_request->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                        </div> --}}

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_file" class="form-label">{{ __('Attachments') }}
                                    {!! commoncomponent()->attachment_view($store_indent_request->image_full_url) !!}</label>
                                <!-- Image preview -->
                                @if (isset($store_indent_request) && !empty(@$store_indent_request->image_full_url))
                                    <img src="{{ $store_indent_request->image_full_url }}" alt="Image Preview"
                                        class="img-thumbnail preview-image"
                                        style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                @endif
                                <!-- File input -->
                                <input class="form-control form-control-sm" name="file" type="file"
                                    id="ir_file">
                                @if ($errors->has('file'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!--begin::Product Search-->
                    @include('pages.partials.product_search.index', [
                        'loop_data' => old('products', @$store_indent_request->store_indent_product_details),
                        'main_data' => $store_indent_request,
                    ])
                    <!--End::Product Items-->

                    <!--begin::Product Search-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="remarks" class=" form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30"
                                    rows="3">{{ old('remarks', $store_indent_request->remarks) }}</textarea>
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

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => false,
                    'back_url' => route('admin.store-indent-request.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
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
                    });
                });
            });
        </script>
        @include('pages.partials.common_script')
        @include('pages.partials.product_search.autocomplete')
        @include('pages.partials.product_search.calculation_script')
    @endsection
</x-default-layout>
