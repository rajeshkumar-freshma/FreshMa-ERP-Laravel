<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Store',
            'menu_1_link' => route('admin.store.index'),
            'menu_2' => 'Add Store',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Store'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="store_details" class="collapse show">
            <!--begin::Form-->
            <form id="store_details_form" class="form" method="POST" action="{{ route('admin.store.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="store_name" class="required form-label">{{ __('Store Name') }}</label>
                                <input type="text" name="store_name" value="{{ old('store_name') }}"
                                    class="form-control form-control-sm form-control-solid" id="store_name"
                                    placeholder="Enter Store Name" required />
                                @if ($errors->has('store_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="slug" class="form-label">{{ __('Slug') }}</label>
                                <input type="text" name="slug" value="{{ old('slug') }}"
                                    class="form-control form-control-sm form-control-solid" id="slug"
                                    placeholder="Enter Slug" />
                                @if ($errors->has('slug'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            @php
                                $store_code = CommonComponent()->invoice_no('store_code');
                            @endphp
                            <div class="mb-5">
                                <label for="store_code" class="form-label required">{{ __('Store Code') }}</label>
                                <input type="text" name="store_code" value="{{ old('store_code', $store_code) }}"
                                    class="form-control form-control-sm form-control-solid" id="store_code"
                                    placeholder="Enter Store Code" required readonly />
                                @if ($errors->has('store_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="warehouse_id" class="form-label required">{{ __('Warehouse') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_id" id="warehouse_id"
                                            aria-label="{{ __('Select Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Warehouse..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach ($warehouses as $key => $warehouse)
                                                <option data-kt-flag="{{ $warehouse->id }}"
                                                    value="{{ $warehouse->id }}"
                                                    {{ $warehouse->id == old('warehouse_id') ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}</option>
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

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="gst_number" class="form-label required">{{ __('GST Number') }}</label>
                                <input type="text" name="gst_number" value="{{ old('gst_number') }}"
                                    class="form-control form-control-sm form-control-solid" id="gst_number"
                                    placeholder="Enter GST Number" maxlength="15" pattern="^[0-9]{2}[A-Za-z]{3}[CPHFATBLJGcphfatblj]{1}[A-Za-z]{1}[0-9]{4}[A-Za-z]{1}[0-9A-Za-z]{1}(Z|z)[0-9A-Za-z]{1}$" title="Please Check The GST Number[Ex:22AAAAA0000A1Z5]" required />
                                @if ($errors->has('gst_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gst_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                                <div class="input-group">
                                    <input id="start_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="start_date" placeholder="Enter Start Date"
                                        value="{{ old('start_date') }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('start_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="phone_number" class="form-label required">{{ __('Phone Number') }}</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                    class="form-control form-control-sm form-control-solid" id="phone_number"
                                    placeholder="Enter Phone Number" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" required />
                                @if ($errors->has('phone_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control form-control-sm form-control-solid" id="email"
                                    placeholder="Enter Email" />
                                @if ($errors->has('email'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="address" class="form-label required">{{ __('Address') }}</label>
                                <textarea name="address" class="form-control form-control-sm form-control-solid" id="address"
                                    placeholder="Enter Address" required>{{ old('address') }}</textarea>
                                @if ($errors->has('address'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="country_id" class="form-label required">{{ __('Country') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="country_id" id="country-dropdown"
                                            aria-label="{{ __('Select Country') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Country..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Country..') }}</option>
                                            @foreach ($countries as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}"
                                                    value="{{ $value->id }}"
                                                    {{ $value->id == old('country_id') ? 'selected' : '' }}>
                                                    {{ $value->currency_code }} - {{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('country_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="state_id" class="form-label required">{{ __('State') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="state_id" id="state-dropdown"
                                            aria-label="{{ __('Select State') }}" data-control="select2"
                                            data-placeholder="{{ __('Select State..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select State..') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('state_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('state_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="city_id" class="form-label required">{{ __('City') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="city_id" id="city-dropdown"
                                            aria-label="{{ __('Select City') }}" data-control="select2"
                                            data-placeholder="{{ __('Select City..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select City..') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('city_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="pincode" class="form-label required">{{ __('Pincode') }}</label>
                                <input type="text" name="pincode" value="{{ old('pincode') }}"
                                    class="form-control form-control-sm form-control-solid" id="pincode"
                                    placeholder="Enter Pincode" required />
                                @if ($errors->has('pincode'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pincode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="latitude" class="form-label">{{ __('Latitude') }}</label>
                                <input type="text" name="latitude" value="{{ old('latitude') }}"
                                    class="form-control form-control-sm form-control-solid" id="latitude"
                                    placeholder="Enter Latitude" />
                                @if ($errors->has('latitude'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('latitude') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="longitude" class="form-label">{{ __('Longitude') }}</label>
                                <input type="text" name="longitude" value="{{ old('longitude') }}"
                                    class="form-control form-control-sm form-control-solid" id="longitude"
                                    placeholder="Enter Longitude" />
                                @if ($errors->has('longitude'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('longitude') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="direction" class="form-label">{{ __('Direction') }}</label>
                                <input type="text" name="direction" value="{{ old('direction') }}"
                                    class="form-control form-control-sm form-control-solid" id="direction"
                                    placeholder="Enter Direction" />
                                @if ($errors->has('direction'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('direction') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
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
                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.store.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.partials.state_city_script')
        @include('pages.partials.date_picker')
    @endsection
</x-default-layout>
