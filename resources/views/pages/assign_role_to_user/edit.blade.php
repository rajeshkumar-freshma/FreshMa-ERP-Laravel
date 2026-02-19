<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Assign Role to Users',
            'menu_1_link' => route('admin.role-management.assign-role-to-users.index'),
            'menu_2' => 'Edit Assign Role to Users',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Assign Role to User'])
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="roles" class="collapse show">
            <form id="roles_form" class="form" method="POST"
                action="{{ route('admin.role-management.assign-role-to-users.update', $selectedUser->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="user_id" class="form-label required">User</label>
                                <select name="user_id" id="user_id" aria-label="Select User" data-control="select2"
                                    data-placeholder="Select User.."
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    required>
                                    <option value="">Select User..</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == old('user_id', $selectedUser->id) ? 'selected' : '' }}>
                                            {{ $user->email }} - {{ $user->first_name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="role_id" class="form-label required">Roles</label>
                                <select name="role_ids[]" id="role_id" aria-label="Select Roles" data-control="select2"
                                    data-placeholder="Select Roles.."
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    >
                                    <option value="">Select Roles..</option>
                                    @foreach (@$roles as $role)
                                        {{-- @if (@$selectedUser->roles->count()) --}}
                                        {{-- @foreach (@$selectedUser->roles as $selectedRoles) --}}
                                        <option value="{{ $role->id }}" {{-- {{ old('role_id', $selectedRoles->id) ? 'selected' : '' }} --}}
                                            @if (in_array($role->id, $selectedUser->roles->pluck('id')->toArray())) @selected(true) @endif>
                                            {{ $role->name }} - {{ $role->guard_name }}</option>
                                        {{-- @endforeach --}}
                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card mt-2">
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.role-management.assign-role-to-users.index') }}">
                            <button type="button" class="btn btn-sm btn-danger btn-active-light-primary me-2">Go
                                Back</button>
                        </a>
                        <button type="submit" class="btn btn-sm btn-success me-2" id="item_details_submit"
                            name="submission_type" value="{{ config('app.submission_type')[1]['value'] }}">
                            @include('partials.general._button-indicator', [
                                'label' => config('app.submission_type')[1]['name'],
                            ])
                        </button>
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
