<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Supplier Wise Purchase Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Supplier Wise Purchase Report',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="{{ route('admin.supplier_wise_purchase_orders') }}" method="GET">
                @csrf
                @method('get')
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label class="form-label">{{ __('Supplier') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="supplier_id" id="supplier_id" aria-label="{{ __('Select Supplier') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Supplier..') }}"
                                        class="form-select form-select-sm form-select-solid" data-live-search="true">
                                        <option value="">{{ __('Select Supplier..') }}</option>
                                        @foreach ($supplier as $key => $item)
                                            <option data-kt-flag="{{ $item->id }}" value="{{ $item->id }}"
                                                {{ $item->id == old('supplier_id') ? 'selected' : '' }}>
                                                {{ $item->first_name }}</option>
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
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="button"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.supplier_wise_purchase_orders') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.report.supplier_wise_purchase_report._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    @section('scripts')
        <script>
            $(function() {
                $("#product_id").select2({
                    minimumInputLength: 3,
                    allowClear: true,
                    placeholder: "Search Product",
                    ajax: {
                        url: "{{ route('admin.getPurchaseProductList') }}",
                        dataType: 'json',
                        type: "GET",
                        quietMillis: 50,
                        data: function(term) {
                            return {
                                term: term
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            });
        </script>
    @endsection
</x-default-layout>
