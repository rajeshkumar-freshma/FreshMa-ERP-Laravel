<!--begin::Tab pane-->
<div class="tab-pane fade" id="add_vendor_advanced" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <!--begin::Meta options-->
        <div class="card card-flush py-4">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="mb-5">
                    <label for="company" class="form-label">{{ __('Company Name') }}</label>
                    <input type="text" name="company" value="{{ old('company', @$data->company) }}" class="form-control form-control-solid" id="company" placeholder="Enter Company Name" />
                    @if ($errors->has('company'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('company') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="website" class="form-label">{{ __('Website') }}</label>
                    <input type="text" name="website" value="{{ old('website', @$data->website) }}" class="form-control form-control-solid" id="website" placeholder="Enter Website" />
                    @if ($errors->has('website'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('website') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="meta_keywords" class="form-label">{{ __('Address') }}</label>
                    <textarea name="address" class="form-control form-control-solid" rows="5" id="address kt_docs_ckeditor_classic" placeholder="Enter Address">{{ old('address', @$data->address) }}</textarea>
                    @if ($errors->has('address'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('address') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="country_id" class="form-label">{{ __('Country') }}</label>

                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="country_id" id="country-dropdown" aria-label="{{ __('Select Country') }}" data-control="select2" data-placeholder="{{ __('Select Country..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                <option value="">{{ __('Select Country..') }}</option>
                                @foreach ($countries as $key => $value)
                                    <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}" {{ $value->id == old('country_id', @$data->country_id) ? 'selected' : '' }}>{{ $value->currency_code }} - {{ $value->name }}</option>
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

                <div class="mb-5">
                    <label for="state_id" class="form-label">{{ __('State') }}</label>

                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="state_id" id="state-dropdown" aria-label="{{ __('Select State') }}" data-control="select2" data-placeholder="{{ __('Select State..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
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

                <div class="mb-5">
                    <label for="city_id" class="form-label">{{ __('City') }}</label>

                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="city_id" id="city-dropdown" aria-label="{{ __('Select City') }}" data-control="select2" data-placeholder="{{ __('Select City..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
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

                <div class="mb-5">
                    <label for="currency_id" class="form-label">{{ __('Currency') }}</label>

                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="currency_id" id="currency-dropdown" aria-label="{{ __('Select Currency') }}" data-control="select2" data-placeholder="{{ __('Select Currency..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                <option value="">{{ __('Select Currency..') }}</option>
                                @foreach ($currencies as $key => $value)
                                    @if (@$data->currency_id != null)
                                        <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}" {{ $value->id == old('currency_id', @$data->currency_id) ? 'selected' : '' }}>{{ $value->symbol }} - {{ $value->code != null ? $value->code : $value->country_code }}</option>
                                    @else
                                        <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}" {{ $value->code == 'INR' ? 'selected' : '' }}>{{ $value->symbol }} - {{ $value->code != null ? $value->code : $value->country_code }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($errors->has('currency_id'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('currency_id') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="gst_number" class="form-label">{{ __('GST Number') }}</label>
                    <input type="text" name="gst_number" value="{{ old('gst_number', @$data->gst_number) }}" class="form-control form-control-solid" id="gst_number" placeholder="Enter GST Number" />
                    @if ($errors->has('gst_number'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('gst_number') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="joined_at" class="form-label">{{ __('Joined Date') }}</label>
                    <div class="input-group">
                        <input id="joined_at" type="text" class="form-control form-control-solid fsh_flat_datepicker" name="joined_at" placeholder="Enter Joined Date" value="{{ old('joined_at', @$data->joined_at) }}" />
                        <span class="input-group-text border-0">
                            <i class="fas fa-calendar"></i>
                        </span>
                    </div>
                    @if ($errors->has('joined_at'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('joined_at') }}</strong>
                        </span>
                    @endif
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::Meta options-->
    </div>
</div>
<!--end::Tab pane-->
