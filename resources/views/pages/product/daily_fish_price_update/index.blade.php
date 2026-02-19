<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Daily Product Price Update',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Daily Product Price Update',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <div class="card-body pt-6">
            <form action="#">
                <div class="row">
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
                                        @foreach (@$store as $key => $store_datas)
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
                            <label for="product_id" class="form-label">{{ __('Product') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="product_id" id="product_id" aria-label="{{ __('Select Product') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Product..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Product..') }}</option>
                                        @foreach (@$products as $key => $products)
                                            <option data-kt-flag="{{ $products->id }}" value="{{ $products->id }}"
                                                {{ $products->id == old('product_id', isset($_REQUEST['product_id']) ?? $_REQUEST['product_id']) ? 'selected' : '' }}>
                                                {{ $products->name }}</option>
                                            {{-- {{ $store_datas->store_name . ' - ' . $store_datas->machine_details->port }}</option> --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-5">
                            <label for="date" class=" form-label">{{ __('Date') }}</label>
                            <div class="input-group">
                                <input id="date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="date"
                                    value="{{ old('date', isset($_REQUEST['date']) ? $_REQUEST['date'] : null) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-5">
                            <label for="filter" class=" form-label"></label>
                            <div class="input-group d-flex justify-content-end">
                                <div class="mt-2">
                                    <button type="button"
                                        class="btn btn-sm btn-success me-2 filter_button ">{{ __('Filter') }}</button>
                                    <a href="{{ route('admin.fish-price-update.index') }}"><button type="button"
                                            class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                        <div class="mb-5">
                            <div class="d-flex justify-content-end">
                                <button type="button"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.sales-credit.index') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </form>
        </div>
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.product.daily_fish_price_update._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
