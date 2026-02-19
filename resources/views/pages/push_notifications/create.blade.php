<x-default-layout>
    <!--begin::Basic info-->
    <form id="daily_stock_update" class="form" method="GET" action="{{ route('admin.daily-stock-update.create') }}"
        enctype="multipart/form-data">
        @csrf
        @method('get')
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Daily Stock Update Datas'])
            <!--begin::Card header-->

            <!--begin::Content-->
            <div id="stock_update">
                <!--begin::Form-->
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="store_id" class="required form-label">{{ __('Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach (@$stores as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == @$store_id ? 'selected' : '' }}>
                                                    {{ $value->store_name }}</option>
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="stock_updated_date"
                                    class="required form-label">{{ __('Stock Updated Date') }}</label>
                                <div class="input-group">
                                    <input id="stock_updated_date" type="button"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker "
                                        name="stock_updated_date" value="{{ @$stock_updated_date }}" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <div class="input-group">
                                    <div class="d-flex justify-content-right py-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                        <a href="{{ route('admin.daily-stock-update.create') }}"><button type="button"
                                                class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Content-->
        </div>
        {{-- product stock history --}}
    </form>
    <form id="daily_stock_update" class="form" method="POST" action="{{ route('admin.daily-stock-update.store') }}"
        enctype="multipart/form-data">
        @csrf
        @method('post')
        @if (isset($product_stock_details) && $product_stock_details->count() > 0)
            <div class="card mt-2">
                <!--begin::Content-->
                <div id="product">
                    <div class="card-body border-top px-9 py-4">
                        <!-- Product details -->
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <div class="mb-5">
                                    <label class="form-label">{{ __('Product') }}</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-5">
                                    <label class="form-label">{{ __('SKU Code') }}</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-5">
                                    <label class="form-label">{{ __('Current Stock') }}</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-5">
                                    <label class="form-label">{{ __('Opeing Stock') }}</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-5">
                                    <label class="form-label">{{ __('Closing Stock') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Loop through product stock details -->

                    @foreach (@$product_stock_details as $product)
                        <!--begin::Card body-->
                        <div class="card-body border-top px-9 py-4">
                            <!-- Product details -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <div class="mb-5">
                                        <p class="">{{ $product->name }}</p>
                                        <input type="text" hidden name="products[]" id="products[]"
                                            value="{{ $product->id }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-5">
                                        <p class="">{{ $product->sku_code }}
                                        </p>
                                    </div>
                                </div>
                                {{-- exact stock value from store stock updates table --}}
                                <input type="text" name="exact_stock_weight[]" id="exact_stock_weight[]"
                                    value="{{ $product->exactstock }}" hidden>
                                <div class="col-md-2">
                                    <div class="mb-5">
                                        <p class="">
                                            {{ $product->exactstock ?? 0 }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-5">
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text border-0"><i class="fas fa-sync"></i></span>
                                            <div class="overflow-hidden flex-grow-1">
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="openingstock[]" id="openingstock[]"
                                                    value="{{ $product->openingstock }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-5">
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text border-0"><i class="fas fa-sync"></i></span>
                                            <div class="overflow-hidden flex-grow-1">
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="closingstock[]" id="closingstock[]"
                                                    value="{{ $product->currentstock }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->
                    @endforeach

                    <div class="col-md-11">
                        <div class="mb-5">
                            <!-- Move this div outside the mb-5 div -->
                            <div class="input-group d-flex justify-content-end">
                                <div class="d-flex justify-content-end py-6">
                                    <button type="submit"
                                        class="btn btn-sm btn-success btn-active-light-primary me-2"
                                        name="submit_type" value="1">{{ __('Daily Stock Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="text" hidden name="store_store_id" id="store_store_id" value="">
            <input type="text" hidden name="stock_updated_on" id="stock_updated_on" value="">
            <div class="card mt-2">
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3 d-flex justify-content-between">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="employee_id" class="form-label">{{ __('Employee') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="employee_id" id="employee_id"
                                            aria-label="{{ __('Select Employee') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Employee..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Employee..') }}</option>
                                            @foreach (@$employees as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}"
                                                    value="{{ $value->id }}"
                                                    {{ $value->id == old('employee_id') ? 'selected' : '' }}>
                                                    {{ $value->first_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('employee_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="reasons" class="form-label">{{ __('Reasons') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <textarea name="reasons" class="form-control form-control-sm form-control-solid" id="reasons"
                                            placeholder="Enter reasons">{{ old('reasons') }}</textarea>
                                    </div>
                                </div>
                                @if ($errors->has('reasons'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('reasons') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-5">
                                <div class="input-group d-flex justify-content-end">
                                    <div class="d-flex justify-content-start py-6">
                                        {{-- <button type="submit" class="btn btn-sm btn-success btn-active-light-primary me-2" name="submit_type" value="1">{{ __('Daily Stock Update') }}</button> --}}
                                        <button type="submit"
                                            class="btn btn-sm btn-warning btn-active-light-primary me-2"
                                            name="submit_type"
                                            value="2">{{ __('Product Adjustment Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
        @endif
    </form>
    @include('pages.store.daily_stock_updated.script')
</x-default-layout>
