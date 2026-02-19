    <div class="card-header" role="button" data-bs-toggle="collapse" data-bs-target="#employee_documents" aria-expanded="false">
        <h3 class="card-title fw-bolder m-0">{{ __('Employee Documents') }}</h3>
        <div class="card-toolbar rotate-180">
            <span class="svg-icon svg-icon-1">
                ...
            </span>
        </div>
    </div>

    <div id="employee_documents" class="collapse">
        <!--begin::Card body-->
        <div class="card-body border-top">
            <!--begin::Input group-->
            <div class="row mb-3">
                <!--begin::Label-->
                <div class="col-md-6">
                    <div class="mb-5">
                        <label for="pan_number" class=" form-label">{{ __('Pan Number') }}</label>
                        <input id="pan_number" type="text" class="form-control form-control-sm form-control-solid" name="pan_number" value="{{ old('pan_number', @$staff_user_info->pan_number) }}" />
                        @if ($errors->has('pan_number'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pan_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-5">
                        <label for="pan_document" class=" form-label">{{ __('Pan Document') }}  {!! commoncomponent()->attachment_view(@$staff_user_info->pan_image_full_url) !!}</label>
                        <div class="input-group">
                            <input id="pan_document" type="file" class="form-control form-control-sm form-control-solid" name="pan_document" value="{{ old('pan_document', @$staff_user_info->pan_document) }}" />
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
                        <input id="aadhar_number" type="text" class="form-control form-control-sm form-control-solid" name="aadhar_number" value="{{ old('aadhar_number', @$staff_user_info->aadhar_number) }}" />
                        @if ($errors->has('aadhar_number'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('aadhar_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-5">
                        <label for="aadhar_document" class=" form-label">{{ __('Aadhar Document') }}  {!! commoncomponent()->attachment_view(@$staff_user_info->aadhar_image_full_url) !!}</label>
                        <div class="input-group">
                            <input id="aadhar_document" type="file" class="form-control form-control-sm form-control-solid" name="aadhar_document" value="{{ old('aadhar_document', @$staff_user_info->aadhar_document) }}" />
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
                        <input id="esi_number" type="text" class="form-control form-control-sm form-control-solid" name="esi_number" value="{{ old('esi_number', @$staff_user_info->esi_number) }}" />
                        @if ($errors->has('esi_number'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('esi_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-5">
                        <label for="esi_document" class=" form-label">{{ __('ESI Document') }}  {!! commoncomponent()->attachment_view(@$staff_user_info->esi_image_full_url) !!}</label>
                        <div class="input-group">
                            <input id="esi_document" type="file" class="form-control form-control-sm form-control-solid" name="esi_document" value="{{ old('esi_document', @$staff_user_info->esi_document) }}" />
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
                        <input id="pf_number" type="text" class="form-control form-control-sm form-control-solid" name="pf_number" value="{{ old('pf_number', @$staff_user_info->pf_number) }}" />
                        @if ($errors->has('pf_number'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pf_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-5">
                        <label for="pf_document" class=" form-label">{{ __('PF Document') }}  {!! commoncomponent()->attachment_view(@$staff_user_info->pf_image_full_url) !!}</label>
                        <div class="input-group">
                            <input id="pf_document" type="file" class="form-control form-control-sm form-control-solid" name="pf_document" value="{{ old('pf_document', @$staff_user_info->pf_document) }}" />
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
                        <input id="bank_name" type="text" class="form-control form-control-sm form-control-solid" name="bank_name" value="{{ old('bank_name', @$staff_user_info->bank_name) }}" />
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
                        <input id="account_number" type="text" class="form-control form-control-sm form-control-solid" name="account_number" value="{{ old('account_number', @$staff_user_info->account_number) }}" />
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
                        <input id="name_as_per_record" type="text" class="form-control form-control-sm form-control-solid" name="name_as_per_record" value="{{ old('name_as_per_record', @$staff_user_info->name_as_per_record) }}" />
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
                        <input id="branch_name" type="text" class="form-control form-control-sm form-control-solid" name="branch_name" value="{{ old('branch_name', @$staff_user_info->branch_name) }}" />
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
                        <input id="ifsc_code" type="text" class="form-control form-control-sm form-control-solid" name="ifsc_code" value="{{ old('ifsc_code', @$staff_user_info->ifsc_code) }}" />
                        @if ($errors->has('ifsc_code'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ifsc_code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-5">
                        <label for="bank_passbook_document" class=" form-label">{{ __('Bank Passbook') }} {!! commoncomponent()->attachment_view(@$staff_user_info->bank_passbook_image_full_url) !!}</label>
                        <div class="input-group">
                            <input id="bank_passbook_document" type="file" class="form-control form-control-sm form-control-solid" name="bank_passbook_document" value="{{ old('bank_passbook_document', @$staff_user_info->bank_passbook_document) }}" />
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
        <!--end::Card body-->
    </div>
