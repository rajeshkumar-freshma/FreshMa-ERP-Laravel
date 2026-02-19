<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Sales Credit',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Sales Credit',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="#">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-5">
                            <label for="bill_no" class="form-label">{{ __('Bill No/Invoice No') }}</label>
                            <input type="text" class="form-control form-control-sm form-control-solid" id="bill_no"
                                name="bill_no" value="{{ old('bill_no') }}" placeholder="Bill/Invoice No" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-5">
                            <label for="store_id" class="form-label">{{ __('Store') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Store..') }}</option>
                                        @foreach ($store as $key => $store_datas)
                                            <option data-kt-flag="{{ $store_datas->id }}" value="{{ $store_datas->id }}"
                                                {{ $store_datas->id == old('store_id', isset($_REQUEST['store_id']) ?? $_REQUEST['store_id']) ? 'selected' : '' }}>
                                                {{ $store_datas->store_name }}</option>
                                            {{-- {{ $store_datas->store_name . ' - ' . $store_datas->machine_details->port }}</option> --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="from_date"
                                    value="{{ old('from_date', isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : null) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-5">
                            <label for="to_date" class=" form-label">{{ __('To Date') }}</label>
                            <div class="input-group">
                                <input id="to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="to_date"
                                    value="{{ old('to_date', isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : null) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-5">
                            <div class="d-flex justify-content-end">
                                <button type="button"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.sales-credit.index') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.sales.sales_credit._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
