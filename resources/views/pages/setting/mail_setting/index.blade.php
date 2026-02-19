<x-default-layout>
    @include('pages.partials.errors')
    {{-- <div class="card">
        <div class="box-content">
            <!--begin::Header-->
            <div>
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

            </div>
            <div class="card-header card-header-stretch">
                <!--begin::Title-->
                <div class="card-title">
                    <h3>Create Email Preference</h3>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="card-body pt-6">
                            <form action="{{ route('admin.mail-setting.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12 mb-5">
                                        <label for="name" class="form-label required">{{ __('Name') }}</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control form-control-sm form-control-solid" id="name"
                                            placeholder="Enter Name.." required />
                                        @error('name')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Add input fields for SMTP settings -->
                                    <div class="col-md-12 mb-5">
                                        <label for="email" class="form-label required">{{ __('Email') }}</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control form-control-sm form-control-solid" id="email"
                                            placeholder="Enter Email.." required />
                                        @error('email')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-5">
                                        <label for="protocol" class="form-label required">{{ __('Protocol') }}</label>
                                        <input type="text" name="protocol" value="{{ old('protocol') }}"
                                            class="form-control form-control-sm form-control-solid" id="protocol"
                                            placeholder="Enter Protocol.." required />
                                        @error('protocol')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="smtp_host" class="form-label required">{{ __('smtpHost') }}</label>
                                        <input type="text" name="smtp_host" value="{{ old('smtp_host') }}"
                                            class="form-control form-control-sm form-control-solid" id="smtp_host"
                                            placeholder="Enter smtp_host.." required />
                                        @error('smtp_host')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="smtp_user_name"
                                            class="form-label required">{{ __('smtpUsername') }}</label>
                                        <input type="text" name="smtp_user_name" value="{{ old('smtp_user_name') }}"
                                            class="form-control form-control-sm form-control-solid" id="smtp_user_name"
                                            placeholder="Enter smtp_user_name.." required />
                                        @error('smtp_user_name')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="smtp_password"
                                            class="form-label required">{{ __('smtpPass') }}</label>
                                        <input type="password" name="smtp_password" value="{{ old('smtp_password') }}"
                                            class="form-control form-control-sm form-control-solid" id="smtp_password"
                                            placeholder="Enter smtp_password.." required autocomplete="off" />
                                        @error('smtp_password')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="smtp_port" class="form-label required">{{ __('smtpPort') }}</label>
                                        <input type="text" name="smtp_port" value="{{ old('smtp_port') }}"
                                            class="form-control form-control-sm form-control-solid" id="smtp_port"
                                            placeholder="Enter smtp_port.." required />
                                        @error('smtp_port')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-5">
                                        <label for="smtp_encryption"
                                            class="form-label required">{{ __('SMTP Encryption') }}</label>
                                        <input type="text" name="smtp_encryption"
                                            value="{{ old('smtp_encryption') }}"
                                            class="form-control form-control-sm form-control-solid" id="smtp_encryption"
                                            placeholder="Enter SMTP Encryption.." required />
                                        @error('smtp_encryption')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-5">
                                        <label for="status" class="form-label required">{{ __('Status') }}</label>
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', @$apiKeySetting->status) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end py-6">
                                            <a href="{{ route('admin.mail-setting.index') }}"><button type="button"
                                                    class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('back') }}</button></a>
                                            <button type="submit"
                                                class="btn btn-sm btn-success me-2">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>


                    </div>
                    <!--begin::Label-->
                </div>
            </div>
        </div>
    </div> --}}
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'SMTP Mail Setup'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details" class="collapse show">
            <!--begin::Form-->
            <form action="{{ route('admin.mail-setting.store', ['id' => @$mail_setting_datas->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('post')
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Mailer Type') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="mailer_type" id="mailer_type"
                                            aria-label="{{ __('Select Mailer Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Mailer Type..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Mailer Type..') }}</option>
                                            @foreach (config('app.mailer_type') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('mailer_type', @$mail_setting_datas->mailer_type ?? '') ? 'selected' : '' }}>
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
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>


                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('From Eamil') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="from_email"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="From Eamil"
                                    value="{{ old('from_email', @$mail_setting_datas->email ?? '') }}" required />
                                @if ($errors->has('from_email'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('From Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="from_name"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="From Name"
                                    value="{{ old('from_name', @$mail_setting_datas->name ?? '') }}" required />
                                @if ($errors->has('from_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('SMTP Host') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="smtp_host"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="SMTP Host"
                                    value="{{ old('smtp_host', @$mail_setting_datas->smtp_host ?? '') }}" required />
                                @if ($errors->has('smtp_host'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('smtp_host') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('SMTP Port') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="smtp_port"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="SMTP Port"
                                    value="{{ old('smtp_port', @$mail_setting_datas->smtp_port ?? '') }}" required />
                                @if ($errors->has('smtp_port'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('smtp_port') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('SMTP User Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="smtp_user_name"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="SMTP User Name"
                                    value="{{ old('smtp_user_name', @$mail_setting_datas->smtp_user_name ?? '') }}"
                                    required />
                                @if ($errors->has('smtp_user_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('smtp_user_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('SMTP User Password') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="password" name="smtp_password"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="SMTP User Password"
                                    value="{{ old('smtp_password', @$mail_setting_datas->smtp_password ?? '') }}"
                                    required />
                                @if ($errors->has('smtp_password'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('smtp_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Encryption type') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="smtp_encryption_type" id="smtp_encryption_type"
                                            aria-label="{{ __('Select Encryption type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Encryption type..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Encryption type..') }}</option>
                                            @foreach (config('app.encription_type') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('smtp_encryption_type', @$mail_setting_datas->smtp_encryption_type ?? '') ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('smtp_encryption_type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('smtp_encryption_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Status') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', @$mail_setting_datas->status) ? 'selected' : '' }}>
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
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Card body-->
        </div>
        <!--end::Card body-->

        <div class="col-md-12">
            <div class="d-flex justify-content-end py-6">
                <a href="{{ route('admin.mail-setting.index') }}"><button type="button"
                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('back') }}</button></a>
                <button type="submit" class="btn btn-sm btn-success me-2">{{ __('Submit') }}</button>
            </div>
        </div>
        <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
    </div>
    <!--end::Basic info-->
    {{-- @include('pages.setting.api_key_setting.script') --}}
</x-default-layout>
