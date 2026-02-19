<!--begin::Tab pane-->
<div class="tab-pane fade" id="add_supplier_advanced" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <!--begin::Meta options-->
        <div class="card card-flush py-4">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
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
                    </div>

                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="website" class="form-label">{{ __('Website') }}</label>
                            <input type="text" name="website" value="{{ old('website', @$data->website) }}" class="form-control form-control-solid" id="website" placeholder="Enter Website" />
                            @if ($errors->has('website'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('website') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
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

                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
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
                    </div>

                    <div class="col-md-6">
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
                    </div>
                </div>

                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
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
                    </div>

                    <div class="col-md-6">
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
                    </div>
                </div>

                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="gst_number" class="form-label">{{ __('GST Number') }}</label>
                            <input type="text" name="gst_number" value="{{ old('gst_number', @$data->gst_number) }}" class="form-control form-control-solid" id="gst_number" placeholder="Enter GST Number" />
                            @if ($errors->has('gst_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('gst_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
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
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="pan_number" class=" form-label">{{ __('Pan Number') }}</label>
                            <input id="pan_number" type="text" class="form-control form-control-sm form-control-solid" name="pan_number" value="{{ old('pan_number', @$data->pan_number) }}" />
                            @if ($errors->has('pan_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pan_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="pan_document" class=" form-label">{{ __('Pan Document') }} {!! commoncomponent()->attachment_view(@$data->pan_image_full_url) !!}</label>
                            <div class="input-group">
                                <input id="pan_document" type="file" class="form-control form-control-sm form-control-solid" name="pan_document" value="{{ old('pan_document', @$data->pan_document) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-file"></i>
                                </span>
                            </div>
                            @if ($errors->has('pan_document'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pan_document') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="aadhar_number" class=" form-label">{{ __('Aadhar Number') }}</label>
                            <input id="aadhar_number" type="text" class="form-control form-control-sm form-control-solid" name="aadhar_number" value="{{ old('aadhar_number', @$data->aadhar_number) }}" />
                            @if ($errors->has('aadhar_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('aadhar_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="aadhar_document" class=" form-label">{{ __('Aadhar Document') }} {!! commoncomponent()->attachment_view(@$data->aadhar_image_full_url) !!}</label>
                            <div class="input-group">
                                <input id="aadhar_document" type="file" class="form-control form-control-sm form-control-solid" name="aadhar_document" value="{{ old('aadhar_document', @$data->aadhar_document) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-file"></i>
                                </span>
                            </div>
                            @if ($errors->has('aadhar_document'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('aadhar_document') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="esi_number" class=" form-label">{{ __('ESI Number') }}</label>
                            <input id="esi_number" type="text" class="form-control form-control-sm form-control-solid" name="esi_number" value="{{ old('esi_number', @$data->esi_number) }}" />
                            @if ($errors->has('esi_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('esi_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="esi_document" class=" form-label">{{ __('ESI Document') }} {!! commoncomponent()->attachment_view(@$data->esi_image_full_url) !!}</label>
                            <div class="input-group">
                                <input id="esi_document" type="file" class="form-control form-control-sm form-control-solid" name="esi_document" value="{{ old('esi_document', @$data->esi_document) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-file"></i>
                                </span>
                            </div>
                            @if ($errors->has('esi_document'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('esi_document') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="pf_number" class=" form-label">{{ __('PF Number') }}</label>
                            <input id="pf_number" type="text" class="form-control form-control-sm form-control-solid" name="pf_number" value="{{ old('pf_number', @$data->pf_number) }}" />
                            @if ($errors->has('pf_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pf_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-5">
                            <label for="pf_document" class=" form-label">{{ __('PF Document') }} {!! commoncomponent()->attachment_view(@$data->pf_image_full_url) !!}</label>
                            <div class="input-group">
                                <input id="pf_document" type="file" class="form-control form-control-sm form-control-solid" name="pf_document" value="{{ old('pf_document', @$data->pf_document) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-file"></i>
                                </span>
                            </div>
                            @if ($errors->has('pf_document'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pf_document') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Bank Details</h5>
                <hr>
                <div class="row mb-3">
                    <!--begin::Label-->
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="bank_name" class=" form-label">{{ __('Bank Name') }}</label>
                            <input id="bank_name" type="text" class="form-control form-control-sm form-control-solid" name="bank_name" value="{{ old('bank_name', @$data->bank_name) }}" />
                            @if ($errors->has('bank_name'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('bank_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="account_number" class=" form-label">{{ __('Account Number') }}</label>
                            <input id="account_number" type="text" class="form-control form-control-sm form-control-solid" name="account_number" value="{{ old('account_number', @$data->account_number) }}" />
                            @if ($errors->has('account_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('account_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="name_as_per_record" class=" form-label">{{ __('Name as per Bank Record') }}</label>
                            <input id="name_as_per_record" type="text" class="form-control form-control-sm form-control-solid" name="name_as_per_record" value="{{ old('name_as_per_record', @$data->name_as_per_record) }}" />
                            @if ($errors->has('name_as_per_record'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name_as_per_record') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="branch_name" class=" form-label">{{ __('Branch Name') }}</label>
                            <input id="branch_name" type="text" class="form-control form-control-sm form-control-solid" name="branch_name" value="{{ old('branch_name', @$data->branch_name) }}" />
                            @if ($errors->has('branch_name'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('branch_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="ifsc_code" class=" form-label">{{ __('IFSC Code') }}</label>
                            <input id="ifsc_code" type="text" class="form-control form-control-sm form-control-solid" name="ifsc_code" value="{{ old('ifsc_code', @$data->ifsc_code) }}" />
                            @if ($errors->has('ifsc_code'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ifsc_code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="bank_passbook_document" class=" form-label">{{ __('Bank Passbook') }} {!! commoncomponent()->attachment_view(@$data->bank_passbook_image_full_url) !!}</label>
                            <div class="input-group">
                                <input id="bank_passbook_document" type="file" class="form-control form-control-sm form-control-solid" name="bank_passbook_document" value="{{ old('bank_passbook_document', @$data->bank_passbook_document) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-file"></i>
                                </span>
                            </div>
                            @if ($errors->has('bank_passbook_document'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('bank_passbook_document') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::Meta options-->
    </div>
</div>
<!--end::Tab pane-->
