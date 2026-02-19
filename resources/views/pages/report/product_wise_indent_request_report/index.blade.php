<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Indent Request Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Indent Request Report',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="" id="submit">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label class="form-label">{{ __('Product') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="product_id" id="product_id" aria-label="{{ __('Select Product') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Product..') }}"
                                        class="form-select form-select-sm form-select-solid" data-live-search="true">
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach (@$products as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == old('product_id',$product_id) ? 'selected' : '' }}>
                                                {{ $value->name }} - {{ $value->slug }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="from_date"
                                    value="{{ old('from_date', isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : $from_date) }}" />
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
                                    name="to_date"
                                    value="{{ old('to_date', isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : $to_date) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="submit"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.productwiseindentrequestreport') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.report.product_wise_indent_request_report._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    {{-- @section('scripts')
        <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    @endsection --}}
</x-default-layout>
