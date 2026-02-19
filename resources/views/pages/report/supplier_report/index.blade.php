<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Suppliers',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Suppliers',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="#">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="supplier_id" class="form-label">{{ __('Supplier') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="supplier_id" id="supplier_id" aria-label="{{ __('Select Supplier') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Supplier..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Supplier..') }}</option>
                                        @foreach (@$suppliers as $key => $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->first_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transaction_from_date"
                                class=" form-label">{{ __('Trasaction From Date') }}</label>
                            <div class="input-group">
                                <input id="transaction_from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="transaction_from_date" value="{{ old('transaction_from_date') }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transaction_to_date" class=" form-label">{{ __('Trasaction To Date') }}</label>
                            <div class="input-group">
                                <input id="transaction_to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="transaction_to_date" value="{{ old('transaction_to_date') }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="purchase_from_date" class=" form-label">{{ __('Purchase From Date') }}</label>
                            <div class="input-group">
                                <input id="purchase_from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="purchase_from_date" value="{{ old('purchase_from_date') }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="purchase_to_date" class=" form-label">{{ __('Purchase To Date') }}</label>
                            <div class="input-group">
                                <input id="purchase_to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="purchase_to_date" value="{{ old('purchase_to_date') }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="purchase_number" class=" form-label">{{ __('Purchase Number') }}</label>
                            <div class="input-group">
                                <input id="purchase_number" type="text"
                                    class="form-control form-control-sm form-control-solid" name="purchase_number"
                                    value="{{ old('purchase_number') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="button"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.suppliers-report.index') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @include('pages.report.supplier_report._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
