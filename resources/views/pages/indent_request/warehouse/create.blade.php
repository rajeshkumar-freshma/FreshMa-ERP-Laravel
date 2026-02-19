<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Warehouse Indent Request',
            'menu_1_link' => route('admin.warehouse-indent-request.index'),
            'menu_2' => 'Add Warehouse Indent Request',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_collapse_header', ['header_name' => 'Search Indent Request', 'card_key' => 'search_requests'])
        <!--begin::Card header-->

        <div id="search_requests" class="collapse">
            <!--begin::Form-->
            <form id="warehouse_indent_request_filter_form" class="form" method="POST" action="{{ route('admin.warehouse-indent-request.create') }}" enctype="multipart/form-data">
                @csrf
                @method('post')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_warehouse" class="form-label">{{ __('Warehouse') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="filter_warehouse_id" id="filter_warehouse" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('filter_warehouse_id', @$_REQUEST['filter_warehouse_id']) ? 'selected' : '' }}> {{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('filter_warehouse_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('filter_warehouse_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_store" class="form-label">{{ __('Store') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="filter_store_id" id="filter_store" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('filter_store_id', @$_REQUEST['filter_store_id']) ? 'selected' : '' }}> {{ $store->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('filter_store_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('filter_store_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_vendor" class="form-label">{{ __('Vendor') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="filter_vendor_id" id="filter_vendor" aria-label="{{ __('Select Vendor') }}" data-control="select2" data-placeholder="{{ __('Select Vendor..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Vendor..') }}</option>
                                            @foreach ($vendors as $key => $vendor)
                                                <option data-kt-flag="{{ $vendor->id }}" value="{{ $vendor->id }}" {{ $vendor->id == old('filter_vendor_id', @$_REQUEST['filter_vendor_id']) ? 'selected' : '' }}> {{ $vendor->first_name . '-' . $vendor->last_name . '-' . $vendor->user_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('filter_vendor_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('filter_vendor_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_store_indent_request" class="form-label">{{ __('Store Indent Request') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="filter_store_indent_request_id[]" id="filter_store_indent_request" aria-label="{{ __('Select Store Indent Request') }}" data-control="select2" data-placeholder="{{ __('Select Store Indent Request..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" multiple>
                                            <option value="">{{ __('Select Store Indent Request..') }}</option>
                                            @foreach ($store_indent_requests as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ isset($_REQUEST['filter_store_indent_request_id']) ? (in_array($store->id, @$_REQUEST['filter_store_indent_request_id']) ? 'selected' : '') : '' }}> {{ $store->request_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('filter_store_indent_request_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('filter_store_indent_request_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_vendor_indent_request" class="form-label">{{ __('Vendor Indent Request') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="filter_vendor_indent_request_id[]" id="filter_vendor_indent_request" aria-label="{{ __('Select Vendor Indent Request') }}" data-control="select2" data-placeholder="{{ __('Select Vendor Indent Request..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" multiple>
                                            <option value="">{{ __('Select Vendor Indent Request..') }}</option>
                                            @foreach ($vendor_indent_requests as $key => $vendor)
                                                <option data-kt-flag="{{ $vendor->id }}" value="{{ $vendor->id }}" {{ isset($_REQUEST['filter_vendor_indent_request_id']) ? (in_array($vendor->id, @$_REQUEST['filter_vendor_indent_request_id']) ? 'selected' : '') : '' }}> {{ $vendor->request_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('filter_vendor_indent_request_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('filter_vendor_indent_request_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_status" class=" form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="filter_status" id="filter_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('filter_status', @$_REQUEST['filter_status']) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('filter_status'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('filter_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_from_date" class=" form-label">{{ __('From Date') }}</label>
                                <div class="input-group">
                                    <input id="filter_from_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="from_date" value="{{ old('from_date', @$_REQUEST['from_date']) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('from_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="filter_to_date" class=" form-label">{{ __('To Date') }}</label>
                                <div class="input-group">
                                    <input id="filter_to_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="to_date" value="{{ old('to_date', @$_REQUEST['to_date']) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('to_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.filter_footer', ['clear_url' => route('admin.warehouse-indent-request.create')])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
    </div>

    @if (count($storeindentDatas) > 0 || count($vendorindentDatas) > 0)
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body border-top">
                <!--begin::Input group-->
                <div class="row">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                            <!--begin:::Tab item-->
                            @if (count($storeindentDatas) > 0 || count($vendorindentDatas) > 0)
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#overall_request_list">Overall</a>
                                </li>
                            @endif
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            @if (count($storeindentDatas) > 0)
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 branch_request_list" data-bs-toggle="tab" href="#branch_request_list">Branch</a>
                                </li>
                            @endif
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            @if (count($vendorindentDatas) > 0)
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#vendor_request_list">Vendor</a>
                                </li>
                            @endif
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->

                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="overall_request_list" role="tab-panel">
                                <div class="d-flex flex-column">
                                    <!--begin::General options-->
                                    <div class="card card-flush">
                                        <table class="table table-sm table-flush align-middle table-row-bordered table-row-solid gy-3 gs-5">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr>
                                                    <th class="min-w-5px"></th>
                                                    <th class="min-w-50px">Product</th>
                                                    <th class="min-w-50px">Unit</th>
                                                    <th class="min-w-50px">Quantity</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="overall_cumulative_data">
                                                @include('pages.indent_request.warehouse.overall_request', ['overall_requests' => $overall_requests])
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::General options-->
                                </div>
                            </div>
                            <!--end::Tab pane-->

                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="branch_request_list" role="tab-panel">
                                <div class="d-flex flex-column">
                                    <!--begin::General options-->
                                    <div class="card card-flush">
                                        <table class="table table-sm table-flush align-middle table-row-bordered table-row-solid gy-3 gs-5">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr>
                                                    <th class="min-w-100px">Request Code</th>
                                                    <th class="min-w-100px pe-0">Request Date</th>
                                                    <th class="min-w-50px">Expected Date</th>
                                                    <th class="min-w-50px">Product</th>
                                                    <th class="min-w-50px">Unit</th>
                                                    <th class="min-w-50px">Quantity</th>
                                                    <th class="min-w-50px">Status</th>
                                                    <th class="min-w-50px">Action</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>
                                                @forelse ($storeindentDatas as $storekey=>$storeindents)
                                                    @if (count($storeindents) > 0)
                                                        <tr>
                                                            <td colspan="9">
                                                                <span class="fw-bold fs-2">{{ $storekey }} - Store</span>
                                                            </td>
                                                        </tr>
                                                        @foreach ($storeindents as $keys => $item)
                                                            <tr>
                                                                <td class="fw-bold fs-6">{{ $item->store_indent_request->request_code }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1">{{ $item->store_indent_request->request_date }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->store_indent_request->expected_date }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->product->name }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->unit!=null ? $item->unit->unit_name . ' (' . $item->unit->unit_short_code . ')' : null }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->request_quantity }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success store_indent_status{{ $item->id }}">
                                                                    @include('pages.partials.statuslabel', ['indent_status' => $item->status])
                                                                </td>
                                                                <td>
                                                                    <select name="status" id="store_indent_status{{ $keys }}" data-id="{{ $item->id }}" data-model="store_indent" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid request_status" data-allow-clear="true">
                                                                        <option value="">{{ __('Select Status..') }}</option>
                                                                        @foreach (config('app.purchase_status') as $key => $value)
                                                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @empty
                                                    <tr>
                                                        <td colspan="9">No Data Available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::General options-->
                                </div>
                            </div>
                            <!--end::Tab pane-->

                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="vendor_request_list" role="tab-panel">
                                <div class="d-flex flex-column">
                                    <!--begin::General options-->
                                    <div class="card card-flush">
                                        <table class="table table-sm table-flush align-middle table-row-bordered table-row-solid gy-3 gs-5">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr>
                                                    <th class="min-w-100px">Request Code</th>
                                                    <th class="min-w-100px pe-0">Request Date</th>
                                                    <th class="min-w-50px">Expected Date</th>
                                                    <th class="min-w-50px">Product</th>
                                                    <th class="min-w-50px">Unit</th>
                                                    <th class="min-w-50px">Quantity</th>
                                                    <th class="min-w-50px">Status</th>
                                                    <th class="min-w-50px">Action</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>
                                                @forelse ($vendorindentDatas as $vendorKey=>$vendorindent)
                                                    @if (count($vendorindent) > 0)
                                                        <tr>
                                                            <td colspan="9">
                                                                <span class="fw-bold fs-2">{{ $vendorKey }} - Vendor</span>
                                                            </td>
                                                        </tr>
                                                        @foreach ($vendorindent as $keys => $item)
                                                            <tr>
                                                                <td class="fw-bold fs-6">{{ $item->vendor_indent_request->request_code }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1">{{ $item->vendor_indent_request->request_date }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->vendor_indent_request->expected_date }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->product->name }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->unit!=null ? $item->unit->unit_name . ' (' . $item->unit->unit_short_code . ')' : null }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->request_quantity }}</td>
                                                                <td class="text-gray-800 fw-bold fs-6 me-1 text-success">
                                                                    @include('pages.partials.statuslabel', ['indent_status' => $item->status])
                                                                </td>
                                                                <td>
                                                                    <select name="status" id="vendor_indent_status{{ $keys }}" data-id="{{ $item->id }}" data-model="vendor_indent" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid request_status" data-allow-clear="true">
                                                                        <option value="">{{ __('Select Status..') }}</option>
                                                                        @foreach (config('app.purchase_status') as $key => $value)
                                                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @empty
                                                    <tr>
                                                        <td colspan="9">No Data Available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::General options-->
                                </div>
                            </div>
                            <!--end::Tab pane-->
                        </div>
                    </div>
                    <!--end::Main column-->
                </div>
            </div>
        </div>
    @endif

    <form id="warehouse_indent_request_form" class="form" method="POST" action="{{ route('admin.warehouse-indent-request.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Add Indent Request'])
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
                                    $invoice_number = commoncomponent()->invoice_no('warehouse_indent');
                                @endphp
                                <label for="wir_code" class="required form-label">{{ __('Indent Request Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="wir_code" name="wir_code" value="{{ old('wir_code', $invoice_number) }}" placeholder="WIR-" required />
                                @if ($errors->has('wir_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('wir_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_request_date" class="required form-label">{{ __('Request Date') }}</label>
                                <div class="input-group">
                                    <input id="ir_request_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="request_date" value="{{ old('request_date') }}" required />
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
                                <label for="ir_expected_date" class="required form-label">{{ __('Expected Date') }}</label>
                                <div class="input-group">
                                    <input id="ir_expected_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="expected_date" value="{{ old('expected_date') }}" required />
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
                                <label for="ir_warehouse" class="required form-label">{{ __('Warehouse') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_id" id="ir_warehouse" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('warehouse_id') ? 'selected' : '' }}> {{ $warehouse->name }}</option>
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
                                <label for="remarks" class=" form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks') }}</textarea>
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
        {{-- @include('pages.partials.transport_detail') --}}
        <!--begin::Actions-->
        @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.warehouse-indent-request.index')])
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
            })
        </script>
        @include('pages.partials.date_picker_script')
        @include('pages.partials.common_script')
        @include('pages.partials.product_search.autocomplete')
        @include('pages.partials.product_search.calculation_script')
    @endsection
</x-default-layout>
