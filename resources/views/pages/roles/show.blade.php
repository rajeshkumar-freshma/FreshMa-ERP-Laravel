<x-default-layout>
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'View Roles Has Permissions'])
        <!--end::Card header-->

        <!--begin::Actions-->
        <div class="card mt-2">
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('admin.role-management.roles.index') }}"><button type="button"
                        class="btn btn-sm btn-danger btn-active-light-primary me-2">{{ __('Go Back') }}</button></a>
            </div>
        </div>
        <!--end::Actions-->
        <!--begin::Content-->
        <div id="roles" class="collapse show">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-4">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Role Name') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <input type="text" name="role_name" id="role_name"
                                class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" placeholder="Name"
                                value="{{ old('role_name', @$role->name) }}" required />
                            @if ($errors->has('role_name'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('role_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!-- Permissions -->

                <!-- Permissions -->
                @if ($permissionsGroup->count())
                    @php $count = 0; @endphp
                    @foreach ($permissionsGroup as $permissionGroup)
                        @if ($count % 4 == 0)
                            <div class="row mb-4">
                        @endif
                        <div class="col-lg-3">
                            <label class="col-form-label fw-bold fs-6 mt-5">{{ $permissionGroup->name }}</label>
                            @foreach ($permissionGroup->permissions as $permission)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input mt-2" name="permissions_ids[]"
                                        @if (in_array($permission->id, $role->permissions->pluck('id')->toArray())) checked @endif
                                        value="{{ old('permissions_ids[]', $permission->id) }}"
                                        id="permission_{{ $permission->id }}">
                                    <label class="form-check-label mt-3" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @php $count++; @endphp
                        @if ($count % 4 == 0 || $loop->last)
            </div>
            @endif
            @endforeach
            @endif
            <!-- End Permissions -->

            <!-- End Permissions -->
        </div>
        <!--end::Card body-->

    </div>
    <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
