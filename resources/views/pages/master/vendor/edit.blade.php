<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Vendor',
            'menu_1_link' => route('admin.customer.index'),
            'menu_2' => 'Edit Vendor',
        ])
    @endsection
    <!--begin::Form-->
    <form id="add_vendor_form" method="POST" action="{{ route('admin.customer.update', $user->id) }}"
        class="form d-flex flex-column flex-lg-row" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--begin::Aside column-->
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <!--begin::Thumbnail settings-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="">Image </h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body text-center pt-0">
                    <!--begin::Image input-->
                    <!--begin::Image input placeholder-->
                    <style>
                        .image-input-placeholder {
                            background-image: url('<?php echo str_replace('&amp;', '&', @$user->user_info->image_full_url); ?>');
                        }

                        [data-bs-theme="dark"] .image-input-placeholder {
                            background-image: url('<?php echo str_replace('&amp;', '&', @$user->user_info->image_full_url); ?>');
                        }
                    </style>
                    <!--end::Image input placeholder-->
                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                        data-kt-image-input="true">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-150px h-150px"></div>
                        <!--end::Preview existing avatar-->
                        <!--begin::Label-->
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <!--begin::Inputs-->
                            <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image
                        files are accepted</div>
                    <!--end::Description-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Thumbnail settings-->

            <!--begin::User Type settings-->
            <div class="card card-flush py-1">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="required">{{ __('User Type') }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body text-center pt-0">
                    <!--begin::User Type-->
                    <!--begin::User Type placeholder-->
                    <div class="mb-1">
                        <div class="input-group input-group-sm flex-nowrap">
                            <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <select name="user_type" id="usertype-dropdown"
                                    aria-label="{{ __('Select User Type') }}" data-control="select2"
                                    data-placeholder="{{ __('Select User Type..') }}"
                                    class="form-select form-select-solid" data-allow-clear="true"
                                    data-hide-search="true" required>
                                    <option value="">{{ __('Select User Type..') }}</option>
                                    @foreach (config('app.user_of_user_type') as $key => $value)
                                        @if ($value['value'] != 2)
                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                                {{ $value['value'] == old('user_type', $user->user_type) ? 'selected' : '' }}>
                                                {{ $value['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($errors->has('user_type'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('user_type') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::User Type-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::User Type settings-->

            <!--begin::Status-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="required">{{ __('Status') }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                class="form-select form-select-solid" data-allow-clear="true" data-hide-search="true"
                                required>
                                <option value="">{{ __('Select Status..') }}</option>
                                @foreach (config('app.statusinactive') as $key => $value)
                                    <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                        {{ $value['value'] == old('status', $user->status) ? 'selected' : '' }}>
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
                <!--end::Card body-->
            </div>
            <!--end::Status-->
        </div>
        <!--end::Aside column-->

        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active general_tab" data-bs-toggle="tab"
                        href="#add_vendor_general">General</a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#add_vendor_advanced">Advanced</a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item commission_percentage_div" style="display : none;">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#add_commission_percentage">Commission Percentage</a>
                </li>
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->
            <!--begin::Tab content-->
            <div class="tab-content">
                <!--begin::Tab pane-->
                <div class="tab-pane fade show active" id="add_vendor_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->

                                <div class="mb-5">
                                    <label for="first_name"
                                        class="required form-label">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name"
                                        value="{{ old('first_name', $user->first_name) }}"
                                        class="form-control form-control-solid" id="first_name"
                                        placeholder="Enter First Name" required />
                                    @if ($errors->has('first_name'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name"
                                        value="{{ old('last_name', $user->last_name) }}"
                                        class="form-control form-control-solid" id="last_name"
                                        placeholder="Enter Last Name" />
                                    @if ($errors->has('last_name'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="user_code" class="form-label">{{ __('User Code') }}</label>
                                    <input type="text" name="user_code"
                                        value="{{ old('user_code', $user->user_code) }}"
                                        class="form-control form-control-solid" id="user_code"
                                        placeholder="Enter User Code" />
                                    @if ($errors->has('user_code'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('user_code') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control form-control-solid" id="email"
                                        placeholder="Enter Email" />
                                    @if ($errors->has('email'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="phone_number"
                                        class="form-label required">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number) }}"
                                        class="form-control form-control-solid" id="phone_number"
                                        placeholder="Enter Phone Number" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" required />
                                    @if ($errors->has('phone_number'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input type="password" name="password" {{-- value="{{ old('password', $user->password) }}" --}}
                                        value="{{ old('password') }}" class="form-control form-control-solid"
                                        id="password" placeholder="Enter Password" />
                                    @if ($errors->has('password'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--end::Input group-->

                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::General options-->
                    </div>
                </div>
                <!--end::Tab pane-->
                @include('pages.master.vendor.advanced', ['data' => $user->user_info])
                @include('pages.master.vendor.commission_percentage', ['data' => $user->vendor_detail])
            </div>
            <!--end::Tab content-->
            @include('pages.partials.form_footer', [
                'is_save' => false,
                'back_url' => route('admin.customer.index'),
            ])
        </div>
        <!--end::Main column-->
    </form>
    <!--end::Form-->
    @section('scripts')
        @include('pages.partials.state_city_script', [
            'country_id' => old('country_id', @$user->user_info->country_id),
            'state_id' => old('state_id', @$user->user_info->state_id),
            'city_id' => old('city_id', @$user->user_info->city_id),
        ])
        @include('pages.partials.date_picker')

        <script>
            $(function() {
                $('#usertype-dropdown').on('change', function() {
                    var value = $(this).val();
                    if (value == 3) {
                        $('.commission_percentage_div').hide();
                        $('.nav-link').removeClass('active');
                        $('.general_tab').addClass('active');
                        $('#add_vendor_general').addClass('active show');
                        $('#add_commission_percentage').removeClass('active show');
                    } else {
                        $('.commission_percentage_div').show();
                        $('.nav-link').removeClass('active');
                        $('.general_tab').addClass('active');
                        $('#add_vendor_general').addClass('active show');
                    }
                })
            });
        </script>
    @endsection
</x-default-layout>
