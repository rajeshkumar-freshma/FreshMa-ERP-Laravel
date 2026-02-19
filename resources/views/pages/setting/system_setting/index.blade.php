<x-default-layout>
    @include('pages.partials.errors')
    <div class="card mb-5 mb-xl-10">
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    {{-- <div class="card">
                        <form action="{{ route('admin.set_config_stored') }}" data-toggle="validator" role="form"
                            autocomplete="off" enctype="multipart/form-data" method="post">
                            @csrf
                            @method('post')
                            <div class="card-header border-0 cursor-pointer" data-bs-toggle="collapse"
                                aria-expanded="true">
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">Site Configuration</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Site Name') }}</label>
                                        <input type="text" name="site_name"
                                            value="{{ old('site_name', @$system_site_data->site_name) }}"
                                            class="form-control tip" id="site_name" required="required" />
                                    </div>
                                    @if ($errors->has('site_name'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('site_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Language') }}</label>
                                        <select name="language" class="form-control tip" id="language"
                                            required="required" style="width:100%;">
                                            <option value="1"
                                                {{ old('language', @$system_site_data->language) == 1 ? 'selected' : '' }}>
                                                Tamil</option>
                                            <option value="2"
                                                {{ old('language', @$system_site_data->language) == 2 ? 'selected' : '' }}>
                                                English</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Currency') }}</label>
                                        <select name="currency" id="currency" aria-label="{{ __('Select Currency') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Currency..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Currency..') }}</option>
                                            @foreach (@$currencies as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == old('currency', @$system_site_data->currency) ? 'selected' : '' }}>
                                                    {{ $value->name }}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('currency'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('currency') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Accounting Method') }}</label>
                                        <select name="accounting_method" id="accounting_method"
                                            aria-label="{{ __('Select Accounting Method') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Accounting Method..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Accounting Method..') }}</option>
                                            @foreach (config('app.accounting_method') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('accounting_method', @$system_site_data->accounting_method) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('accounting_method'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('accounting_method') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Default Email') }}</label>

                                        <input type="email" name="email"
                                            value="{{ old('email', @$system_site_data->email) }}"
                                            class="form-control tip" required="required" id="email" />
                                        @if ($errors->has('email'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-5 col-form-label required fw-bold fs-6">{{ __('Default Vendor Group') }}</label>
                                        <select name="customer_group" id="customer_group"
                                            aria-label="{{ __('Select Vendor Group') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Vendor Group..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Vendor Group..') }}</option>
                                            @foreach (config('app.default_customer_group') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('customer_group', @$system_site_data->customer_group) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('customer_group'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('customer_group') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Default Price Group') }}</label>
                                        <select name="price_group" id="price_group"
                                            aria-label="{{ __('Select Price Group') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Price Group..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Price Group..') }}</option>
                                            @foreach (config('app.default_price_group') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('price_group', @$system_site_data->price_group) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('price_group'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('price_group') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Maintenance Mode') }}</label>
                                        <select name="mmode" id="mmode"
                                            aria-label="{{ __('Select Maintenance Mode') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Maintenance Mode..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Maintenance Mode..') }}</option>
                                            @foreach (config('app.maintenance_mode') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('mmode', @$system_site_data->mmode) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('mmode'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('mmode') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Theme') }}</label>
                                        <select name="theme" id="theme" aria-label="{{ __('Select Theme') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Theme..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Theme..') }}</option>
                                            @foreach (config('app.site_theme') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('theme', @$system_site_data->theme) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('theme'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('theme') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Login Captcha') }}</label>
                                        <select name="captcha" id="captcha"
                                            aria-label="{{ __('Select Login Captcha') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Login Captcha..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Login Captcha..') }}</option>
                                            @foreach (config('app.ableDisable') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('captcha', @$system_site_data->captcha) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('captcha'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('captcha') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-6 col-form-label required fw-bold fs-6">{{ __(' Number of days to disable editing') }}</label>
                                        <input type="text" name="disable_editing"
                                            value="{{ old('disable_editing', @$system_site_data->disable_editing) }}"
                                            class="form-control tip" id="disable_editing" required="required" />
                                        @if ($errors->has('disable_editing'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('disable_editing') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Rows per page') }}</label>
                                        <input type="text" name="rows_per_page"
                                            value="{{ old('rows_per_page', @$system_site_data->rows_per_page) }}"
                                            class="form-control tip" id="rows_per_page" required="required" />
                                        @if ($errors->has('rows_per_page'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('rows_per_page') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Date Format') }}</label>
                                        <select name="dateformat" id="dateformat"
                                            aria-label="{{ __('Select Date Format') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Date Format..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Date Format..') }}</option>
                                            @foreach (config('app.date_format') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('dateformat', @$system_site_data->dateformat) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('dateformat'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('dateformat') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Timezone') }}</label>
                                        <select name="timezone" id="timezone"
                                            aria-label="{{ __('Select Timezone') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Timezone..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Timezone..') }}</option>
                                            @foreach (config('app.time_zone') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('timezone', @$system_site_data->timezone) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('timezone'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('timezone') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Default Warehouse') }}</label>
                                        <select name="warehouse" id="warehouse"
                                            aria-label="{{ __('Select Default Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Default Warehouse..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Default Warehouse..') }}</option>
                                            @foreach (@$warehouses as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}"
                                                    value="{{ $value->id }}"
                                                    {{ $value->id == old('warehouse', @$system_site_data->warehouse) ? 'selected' : '' }}>
                                                    {{ $value->name }}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('warehouse'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('warehouse') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' PDF Library') }}</label>
                                        <select name="pdf_lib" id="pdf_lib"
                                            aria-label="{{ __('Select PDF Library') }}" data-control="select2"
                                            data-placeholder="{{ __('Select PDF Library..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select PDF Library..') }}</option>
                                            @foreach (config('app.pdf_library') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('pdf_lib', @$system_site_data->pdf_lib) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('pdf_lib'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('pdf_lib') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="apis">
                                            <label
                                                class="col-lg-5 col-form-label required fw-bold fs-6">{{ __(' APIs Feature') }}</label>
                                            <select name="apis" id="apis"
                                                aria-label="{{ __('Select APIs Featur') }}" data-control="select2"
                                                data-placeholder="{{ __('Select APIs Featur..') }}"
                                                class="form-select form-select-solid" data-allow-clear="true">
                                                <option value="">{{ __('Select APIs Featur..') }}</option>
                                                @foreach (config('app.ableDisable') as $key => $value)
                                                    <option data-kt-flag="{{ $value['value'] }}"
                                                        value="{{ $value['value'] }}"
                                                        {{ $value['value'] == old('apis', @$system_site_data->apis) ? 'selected' : '' }}>
                                                        {{ $value['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('apis'))
                                                <span class="fv-plugins-message-container invalid-feedback"
                                                    role="alert">
                                                    <strong>{{ $errors->first('apis') }}</strong>
                                                </span>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __(' Use code for slug') }}</label>
                                        <select name="use_code_for_slug" id="use_code_for_slug"
                                            aria-label="{{ __('Select Use code for slug') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Use code for slug..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Use code for slug..') }}</option>
                                            @foreach (config('app.ableDisable') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('use_code_for_slug', @$system_site_data->use_code_for_slug) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('use_code_for_slug'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('use_code_for_slug') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--begin::Card footer-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                <button class="btn btn-primary  px-6">Save Changes</button>
                            </div>
                            <!--end::Card footer-->
                        </form>
                    </div> --}}
                    {{-- <div class="card">
                        <form action="{{ route('admin.system-setting.store') }}" data-toggle="validator" role="form"
                            autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            @csrf
                            @method('post')
                            <div class="col-lg-12">
                                <fieldset class="scheduler-border">
                                    <div class="card-header border-0 cursor-pointer" role="button"
                                        data-bs-toggle="collapse" data-bs-target="#kt_account_email_preferences"
                                        aria-expanded="true" aria-controls="kt_account_email_preferences">
                                        <div class="card-title m-0">
                                            <h3 class="fw-bold m-0">Site Configuration</h3>
                                        </div>
                                    </div>
                                    @php
                                        $counter = 0;
                                    @endphp
                                    @foreach (config('app.site_config_lables') as $item)
                                        @if ($counter % 3 == 0)
                                            <div class="row">
                                        @endif
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <span class="form-check-label d-flex flex-column align-items-start">
                                                    <span class="fw-bold fs-5 mb-0 p-4" for="values{{ $counter }}">
                                                        {{ ucfirst(str_replace('_', ' ', $item['name'])) }}</span>
                                                </span>
                                                <input type="text"
                                                    name="values[{{ $item['name'] }}]{{ $counter }}"
                                                    value="{{ old('values', @$item['value'] ?? '') }}"
                                                    class="form-control tip" id="values{{ $counter }}"
                                                    data-original-title="" title="">
                                            </div>
                                        </div>
                                        @if (($counter + 1) % 3 == 0 || $loop->last)
                            </div>
                            @endif
                            @php
                                $counter++;
                            @endphp
                            @endforeach
                            <!-- Other prefix fields... -->
                            <!-- Add your other prefix fields here -->
                            </fieldset>

                            <!--begin::Card footer-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                <button class="btn btn-primary  px-6">Save Changes</button>
                            </div>
                            <!--end::Card footer-->
                        </form>
                    </div> --}}

                    <div class="card">
                        <form action="{{ route('admin.system-setting.store') }}" data-toggle="validator"
                            role="form" autocomplete="off" enctype="multipart/form-data" method="post"
                            accept-charset="utf-8">
                            @csrf
                            @method('post')
                            <div class="col-lg-12 card-body pt-3">
                                <fieldset class="scheduler-border">
                                    {{-- <legend class="scheduler-border">Prefix</legend> --}}
                                    <div class="card-header border-0 cursor-pointer" role="button"
                                        data-bs-toggle="collapse" data-bs-target="#kt_account_email_preferences"
                                        aria-expanded="true" aria-controls="kt_account_email_preferences">
                                        <div class="card-title m-0">
                                            <h3 class="fw-bold m-0">Set Prefix</h3>
                                        </div>
                                    </div>
                                    @php
                                        $counter = 0;
                                    @endphp
                                    @foreach (@$system_settings_data as $item)
                                        @if ($counter % 3 == 0)
                                            <div class="row">
                                        @endif
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <span class="form-check-label d-flex flex-column align-items-start">
                                                    <span class="fw-bold fs-5 mb-0 pl-0 pt-4 pr-4 pb-4"
                                                        for="values{{ $counter }}">
                                                        {{ ucfirst(str_replace('_', ' ', $item->key)) }}</span>
                                                </span>
                                                {{-- <h4 class="control-label p-4" for="values{{ $counter }}">
                                                    {{ ucfirst(str_replace('_', ' ', $item->key)) }}</h4> --}}
                                                <input type="text"
                                                    name="values[{{ $item->key }}]{{ $counter }}"
                                                    value="{{ old('values', @$item->value ?? '') }}"
                                                    class="form-control tip" id="values{{ $counter }}"
                                                    data-original-title="" title="">
                                            </div>
                                        </div>
                                        @if (($counter + 1) % 3 == 0 || $loop->last)
                            </div>
                            @endif
                            @php
                                $counter++;
                            @endphp
                            @endforeach
                            <!-- Other prefix fields... -->
                            <!-- Add your other prefix fields here -->
                            </fieldset>

                            <!--begin::Card footer-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                <button class="btn btn-primary  px-6">Save Changes</button>
                            </div>
                            <!--end::Card footer-->
                        </form>
                    </div>
                    <!--begin::Label-->

                    <div class="card  mb-5 mb-xl-10">
                        <!--begin::Card header-->
                        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                            data-bs-target="#kt_account_notifications" aria-expanded="true"
                            aria-controls="kt_account_notifications">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Cron Run</h3>
                            </div>
                            {{-- <form action="{{ route('admin.auto_cron_run') }}" method="post">
                                @csrf
                                @method('post')
                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <button type="submit" class="btn btn-success  px-6">Run</button>
                                </div>
                            </form> --}}
                            <!-- Your blade template -->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <form action="{{ route('admin.auto_cron_run') }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-success align-self-center px-6">Run All
                                        Cron</button>
                                </form>
                            </div>
                        </div>
                        <!--begin::Card header-->
                        {{--
                        <!--begin::Content-->
                        <div id="kt_account_settings_notifications" class="collapse show">
                            <!--begin::Form-->
                            <form class="form">
                                <!--begin::Card body-->
                                <div class="card-body border-top px-9 pt-3 pb-4">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed border-gray-300 align-middle gy-6">
                                            <tbody class="fs-6 fw-semibold">
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td class="min-w-250px ">Notifications</td>
                                                    <td class="w-125px">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="kt_settings_notification_email"
                                                                checked data-kt-check="true"
                                                                data-kt-check-target="[data-kt-settings-notification=email]" />
                                                            <label class="form-check-label ps-2"
                                                                for="kt_settings_notification_email">
                                                                Run
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="w-125px">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="kt_settings_notification_phone"
                                                                checked data-kt-check="true"
                                                                data-kt-check-target="[data-kt-settings-notification=phone]" />
                                                            <label class="form-check-label ps-2"
                                                                for="kt_settings_notification_phone">
                                                                Phone
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                               <!--begin::Table row-->

                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Billing Updates</td>
                                                    <td>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="billing1" checked
                                                                data-kt-settings-notification="email" />
                                                            <label class="form-check-label ps-2"
                                                                for="billing1"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="billing2" checked
                                                                data-kt-settings-notification="phone" />
                                                            <label class="form-check-label ps-2"
                                                                for="billing2"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--begin::Table row-->

                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>New Team Members</td>
                                                    <td>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="team1" checked
                                                                data-kt-settings-notification="email" />
                                                            <label class="form-check-label ps-2"
                                                                for="team1"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="team2"
                                                                data-kt-settings-notification="phone" />
                                                            <label class="form-check-label ps-2"
                                                                for="team2"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--begin::Table row-->

                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Completed Projects</td>
                                                    <td>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="project1"
                                                                data-kt-settings-notification="email" />
                                                            <label class="form-check-label ps-2"
                                                                for="project1"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="project2" checked
                                                                data-kt-settings-notification="phone" />
                                                            <label class="form-check-label ps-2"
                                                                for="project2"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--begin::Table row-->

                                                <!--begin::Table row-->
                                                <tr>
                                                    <td class="border-bottom-0">Newsletters</td>
                                                    <td class="border-bottom-0">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="newsletter1"
                                                                data-kt-settings-notification="email" />
                                                            <label class="form-check-label ps-2"
                                                                for="newsletter1"></label>
                                                        </div>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="newsletter2"
                                                                data-kt-settings-notification="phone" />
                                                            <label class="form-check-label ps-2"
                                                                for="newsletter2"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--begin::Table row-->
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->

                                <!--begin::Card footer-->
                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <button class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                    <button class="btn btn-primary  px-6">Save Changes</button>
                                </div>
                                <!--end::Card footer-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Content--> --}}
                        <!--begin::Card footer-->

                        <!--end::Card footer-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Notifications-->
</x-default-layout>
