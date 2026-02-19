<x-default-layout>
        <!--begin::Card-->
        @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Bulk Product Transfer',
            'menu_1_link' => route('admin.bulk-product-transfer.index'),
            'menu_2' => 'Edit Bulk Product Transfer',
        ])
    @endsection
    <!--begin::Basic info-->
    <form id="product_transfer_form" class="form" method="POST"
        action="{{ route('admin.bulk-product-transfer.update', $product_bulk_transfer->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Product Bulk Transfer'])
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
                                <label for="transfer_order_number"
                                    class="required form-label">{{ __('Product Bulk Transfer Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    id="transfer_order_number" name="transfer_order_number"
                                    value="{{ old('transfer_order_number', $product_bulk_transfer->transfer_order_number) }}"
                                    placeholder="PO Number" required />
                                @if ($errors->has('transfer_order_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_order_number') }}</strong>
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
                                        <select name="from_warehouse_id" id="from_warehouse_id"
                                            aria-label="{{ __('Select Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Warehouse..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}"
                                                    {{ $warehouse->id == old('from_warehouse_id', $product_bulk_transfer->from_warehouse_id) ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}</option>
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

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transfer_created_date"
                                    class="required form-label">{{ __('Transfer Date') }}</label>
                                <div class="input-group">
                                    <input id="transfer_created_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="transfer_created_date"
                                        value="{{ old('transfer_created_date', $product_bulk_transfer->transfer_created_date) }}"
                                        placeholder="Return Date" required />
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
                                <label for="transfer_received_date" class="form-label">{{ __('Receive Date') }}</label>
                                <div class="input-group">
                                    <input id="transfer_received_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="transfer_received_date"
                                        value="{{ old('transfer_received_date', $product_bulk_transfer->transfer_received_date) }}"
                                        placeholder="Return Date" required />
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
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', $product_bulk_transfer->status) ? 'selected' : '' }}>
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
                    <hr>
                    <div class="product_list_append_div"></div>
                    <!--begin::Product Search-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="status" class="form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30"
                                    rows="3">{{ old('remarks', $product_bulk_transfer->remarks) }}</textarea>
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
        <!--begin::Actions-->
        {{-- @include('pages.partials.form_footer', ['is_save' => false, 'back_url' => route('admin.bulk-product-transfer.index'), 'is_bulk_product_transfer' => true]) --}}

        <div class="card mt-2">
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('admin.bulk-product-transfer.index') }}"><button type="button"
                        class="btn btn-sm btn-danger btn-active-light-primary me-2">{{ __('Go Back') }}</button></a>

                <button type="reset"
                    class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Reset') }}</button>
                <button type="submit" class="btn btn-sm btn-primary me-2" id="item_details_submit"
                    name="submission_type" value={{ config('app.submission_type')[0]['value'] }}>
                    @include('partials.general._button-indicator', [
                        'label' => __(config('app.submission_type')[0]['name']),
                    ])
                </button>

                <button type="submit" class="btn btn-sm btn-success me-2" id="item_details_submit"
                    name="submission_type" value={{ config('app.submission_type')[1]['value'] }}>
                    @include('partials.general._button-indicator', [
                        'label' => __(config('app.submission_type')[1]['name']),
                    ])
                </button>
                <button type="submit" class="btn btn-sm btn-info" id="distribution_submit"
                    name="submission_type" value='distribution_submit'>
                    @include('partials.general._button-indicator', ['label' => __('Convert to Distribution')])
                </button>
            </div>
        </div>
        <!--end::Actions-->
    </form>

    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>
            $(function() {
                $('#from_warehouse_id').on('change', function() {
                    getbulkproducttransferlist();
                }).trigger('change');

                $('#transfer_created_date').on('change', function() {
                    getbulkproducttransferlist();
                }).trigger('change');
            })

            function getbulkproducttransferlist() {
                var from_warehouse_id = $('#from_warehouse_id').find(':selected').val();
                var transfer_created_date = $('#transfer_created_date').val();
                if (from_warehouse_id != null && from_warehouse_id != '' && transfer_created_date != null &&
                    transfer_created_date != '') {
                    $.ajax({
                        type: 'get',
                        url: "{{ route('admin.bulk_product_list.render') }}",
                        data: {
                            from_warehouse_id: from_warehouse_id,
                            transfer_created_date: transfer_created_date
                        },
                        success: function(res) {
                            $('.product_list_append_div').html(res)
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            if (xhr.status == 419) {
                                alert(xhr.responseText) // text of error
                            } else if (xhr.status == 401) {
                                window.location.reload();
                            }
                        }
                    })
                }
            }
        </script>

        @include('pages.partials.date_picker_script')
    @endsection
</x-default-layout>
