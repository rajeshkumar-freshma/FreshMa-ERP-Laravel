<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Daily Sales Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Daily Sales Report',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        {{-- <div class="card-body pt-6">
            <form action="" id="submit">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label class="form-label">{{ __('Product') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="store_id" id="store_id" aria-label="{{ __('Select Product') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Product..') }}"
                                        class="form-select form-select-sm form-select-solid" data-live-search="true">
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach (@$stores as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == old('store_id') ? 'selected' : '' }}>
                                                {{ $value->store_name }} - {{ $value->slug }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="delivered_date" class=" form-label">{{ __('Delivered Date') }}</label>
                            <div class="input-group">
                                <input id="delivered_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="delivered_date"
                                    value="{{ old('delivered_date', isset($_REQUEST['delivered_date']) ? $_REQUEST['delivered_date'] : null) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="submit"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.dailysalesreport') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div> --}}

        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.report.daily_sales_report._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    {{-- @section('scripts')
        <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    @endsection --}}
</x-default-layout>
