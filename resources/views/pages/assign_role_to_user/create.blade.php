<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Assign Role to Users',
            'menu_1_link' => route('admin.role-management.assign-role-to-users.index'),
            'menu_2' => 'Assign Role to Users',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Role Setup'])
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="roles" class="collapse show">
            <form id="roles_form" class="form" method="POST" action="{{ route('admin.role-management.assign-role-to-users.store') }}" enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="user_id" class="form-label required">{{ __('User') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="user_id" id="user-dropdown" aria-label="{{ __('Select User') }}" data-control="select2" data-placeholder="{{ __('Select User..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select User..') }}</option>
                                            @foreach ($users as $key => $value)
                                            <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}" {{ $value->id == old('user_id') ? 'selected' : '' }}>
                                                {{ $value->email }} - {{ $value->first_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('user_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="role_id" class="form-label required">{{ __('Roles') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="role_ids[]" id="roles-dropdown" aria-label="{{ __('Select Roles') }}" data-control="select2" data-placeholder="{{ __('Select Roles..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required >
                                            <option value="">{{ __('Select Roles..') }}</option>
                                            @foreach ($roles as $key => $value)
                                            <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}" {{ $value->id == old('role_id') ? 'selected' : '' }}>
                                                {{ $value->name }} - {{ $value->guard_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('role_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('role_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- Permissions -->

                        <!-- End Permissions -->

                        <!-- End Permissions -->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Actions-->
                    <div class="card mt-2">
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <a href="{{ route('admin.role-management.assign-role-to-users.index') }}"><button type="button" class="btn btn-sm btn-danger btn-active-light-primary me-2">{{ __('Go Back') }}</button></a>

                            <button type="submit" class="btn btn-sm btn-success me-2" id="item_details_submit" name="submission_type" value="{{ config('app.submission_type')[1]['value'] }}">
                                @include('partials.general._button-indicator', [
                                'label' => __(config('app.submission_type')[1]['name']),
                                ])
                            </button>
                        </div>
                    </div>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
