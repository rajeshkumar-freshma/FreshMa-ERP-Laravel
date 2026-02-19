<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Machine Sales Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Machine Sales Report',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="#">
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="mb-5">
                            <label for="store_id" class="form-label">{{ __('Store') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="store_id" id="store_id" aria-label="{{ __('Select Branch') }}" data-control="select2" data-placeholder="{{ __('Select Branch..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" >
                                        <option value="">{{ __('Select Branch..') }}</option>
                                        @foreach ($stores as $key => $store)
                                            <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('store_id') ? 'selected' : '' }}> {{ $store->name . ' - ' . $store->store_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="warehouse_id" class="form-label">{{ __('Warehouse') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="warehouse_id" id="warehouse_id" aria-label="{{ __('Select Warehouse') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Warehouse..') }}</option>
                                        @foreach (@$warehouse as $key => $values)
                                            <option data-kt-flag="{{ $values->id }}" value="{{ $values->id }}"
                                                {{ $values->id == old('warehouse_id') ? 'selected' : '' }}>
                                                {{ $values->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="machine_id" class="form-label">{{ __('Machine') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="machine_id" id="machine_id" aria-label="{{ __('Select Machine') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Machine..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Machine..') }}</option>
                                        @foreach (@$machine_details as $key => $machine_detail)
                                            <option data-kt-flag="{{ $machine_detail->id }}"
                                                value="{{ $machine_detail->id }}"
                                                {{ $machine_detail->id == old('machine_id') ? 'selected' : '' }}>
                                                {{ $machine_detail->MachineName . ' - ' . $machine_detail->Port }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="from_date" value="{{ old('from_date') }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_date" class=" form-label">{{ __('To Date') }}</label>
                            <div class="input-group">
                                <input id="to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="to_date" value="{{ old('to_date') }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6 pt-8">
                                <button type="button"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.branch-machine-sale.index') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                                {{-- <a href="{{ route('admin.branch-machine-sale.export') }}"><button type="button"
                                            class="btn btn-sm btn-primary btn-active-light-primary me-2">{{ __('Export') }}</button></a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @include('pages.report.sales_report._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
