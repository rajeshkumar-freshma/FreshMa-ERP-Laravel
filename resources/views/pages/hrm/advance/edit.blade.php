<x-default-layout>
    @section('_toolbar')
        @php
            $data['title'] = 'Staff Advance';
            $data['menu_1'] = 'Staff Advance';
            $data['menu_1_link'] = route('admin.staff-advance.index');
            $data['menu_2'] = 'Edit';
        @endphp
        @include(config('settings.KT_THEME_LAYOUT_DIR') . '/partials/sidebar-layout/_toolbar', ['data' => $data])
    @endsection

    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Staff Advance'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="staff_advance" class="collapse show">
            <!--begin::Form-->
            <form id="staff_advance_form" class="form" method="POST" action="{{ route('admin.staff-advance.update', $staff->id) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="first_name" class="required form-label">{{ __('First Name') }}</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" class="form-control form-control-sm form-control-solid" id="first_name" placeholder="Enter Name" required />
                                @if ($errors->has('first_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="last_name" class="form-label required">{{ __('Last Name') }}</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" class="form-control form-control-sm form-control-solid" id="last_name" placeholder="Enter Name" required/>
                                @if ($errors->has('last_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="phone_number" class="form-label required">{{ __('Phone Number') }}</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $staff->phone_number) }}" class="form-control form-control-sm form-control-solid" id="phone_number" placeholder="Enter Phone Number" required />
                                @if ($errors->has('phone_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="email" class="form-label required">{{ __('Email') }}</label>
                                <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="form-control form-control-sm form-control-solid" id="email" placeholder="Enter Email" required/>
                                @if ($errors->has('email'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" name="password" value="{{ old('password') }}" class="form-control form-control-sm form-control-solid" id="password" placeholder="Enter Password" />
                                @if ($errors->has('password'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="address" class="form-label">{{ __('Address') }}</label>
                                <textarea name="address" class="form-control form-control-sm form-control-solid" id="address" placeholder="Enter Address">{{ old('address', $staff->user_info->address) }}</textarea>
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
                                        <select name="country_id" id="country-dropdown" aria-label="{{ __('Select Country') }}" data-control="select2" data-placeholder="{{ __('Select Country..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Country..') }}</option>
                                            @foreach ($countries as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}" {{ $value->id == old('country_id', $staff->user_info->country_id) ? 'selected' : '' }}>{{ $value->currency_code }} - {{ $value->name }}</option>
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
                                        <select name="state_id" id="state-dropdown" aria-label="{{ __('Select State') }}" data-control="select2" data-placeholder="{{ __('Select State..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
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
                                        <select name="city_id" id="city-dropdown" aria-label="{{ __('Select City') }}" data-control="select2" data-placeholder="{{ __('Select City..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
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
                                <label for="pincode" class="form-label">{{ __('Joined Date') }}</label>
                                <div class="input-group">
                                    <input id="joined_at" type="text" class="form-control form-control-solid fsh_flat_datepicker" name="joined_at" placeholder="Enter Joined Date" value="{{ old('joined_at', @$staff->user_info->joined_at) }}" />
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

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $staff->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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

                        <div class="col-md-6">
                            <style>
                                .image-input-placeholder {
                                    background-image: url('<?php echo str_replace('&amp;', '&', $staff->user_info->image_full_url); ?>');
                                }

                                [data-bs-theme="dark"] .image-input-placeholder {
                                    background-image: url('<?php echo str_replace('&amp;', '&', $staff->user_info->image_full_url); ?>');
                                }
                            </style>
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Profile Photo') }}</label>
                                <br>
                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-1" data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
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
                @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.staff-advance.index')])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
        @include('pages.partials.date_picker')
    @endsection
</x-default-layout>
