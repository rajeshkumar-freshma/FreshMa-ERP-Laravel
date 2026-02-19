<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Staff Attendence',
            'menu_1_link' => route('admin.staff_attendance.index'),
            'menu_2' => 'Add Staff Attendence',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Staff Attendance'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="employee_details" class="collapse show">
            <!--begin::Form-->
            <form id="employee_details_form" class="form" method="POST"
                action="{{ route('admin.staff_attendance.store') }}" enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label
                                    for="attendance_date"class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Select Attendance Date') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input id="attendance_date" type="text"
                                        class="form-control form-control-solid fsh_flat_datepicker"
                                        name="attendance_date" placeholder="Enter Start Date"
                                        value="{{ old('attendance_date') }}" />
                                </div>
                                @if ($errors->has('attendance_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('attendance_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label onchange="getEmployees()"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Select Store') }}</label>
                                <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                    <option value="">Select Store</option>
                                    @foreach ($store as $key => $details)
                                        <option value="{{ $details->id }}"
                                            {{ $details->id == old('store_id') ? 'selected' : '' }} class="optionGroup">
                                            {{ ucFirst($details->store_name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('store_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="col-md-6" id="employee_hide">
                            <div class="mb-5">
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Employee') }}</label>
                                <select name="employee_id[]" id="employee_id" aria-label="{{ __('Select Employee') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Employee..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    multiple>
                                    <option value="">Select Employee</option>
                                </select>

                                @if ($errors->has('employee_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="mb-5">
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Attendance Type') }}</label>
                                <div class="d-flex flex-wrap">
                                    @foreach (config('app.attendance_type') as $type)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="attendance_type"
                                                value="{{ $type['value'] }}"
                                                id="{{ 'attendance_type_' . $type['value'] }}">
                                            <label class="form-check-label"
                                                for="{{ 'attendance_type_' . $type['value'] }}">
                                                <span class="badge bg-{{ $type['color'] }}" style="font-size: 14px;">
                                                    <i class="fas fa-{{ $type['icon_name'] }}"></i>
                                                    {{ $type['name'] }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($errors->has('attendance_type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('attendance_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="in_datetime"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('In Time') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-calendar"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input type="datetime-local" name="in_datetime"
                                            value="{{ old('in_datetime') }}"
                                            class="form-control form-control-sm form-control-solid" id="in_datetime"
                                            placeholder="Enter In Time" required />
                                    </div>
                                </div>
                                @if ($errors->has('in_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('in_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="out_datetime"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Out Time') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-calendar"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input type="datetime-local" name="out_datetime"
                                            value="{{ old('out_datetime') }}"
                                            class="form-control form-control-sm form-control-solid" id="out_datetime"
                                            placeholder="Enter Out Time" required />
                                    </div>
                                </div>
                                @if ($errors->has('out_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('out_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
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
                        </div>
                    </div>
                     <div class="card-body ">
                            <table class="table align-middle table-row-dashed fs-6 gy-3">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th scope="col">Employee</th>
                                        <th scope="col">In Time</th>
                                        <th scope="col">Out Time</th>
                                        <th scope="col">Absent</th>
                                        <th scope="col">Present</th>
                                        <th scope="col">Halfday</th>
                                        <th scope="col">Holiday</th>
                                        <th scope="col">Leave</th>
                                        <th scope="col">Vacancy</th>
                                    </tr>
                                </thead>
                                <tbody class="employee_details_append">


                                </tbody>
                            </table>
                        </div>
                    {{-- <div class="employee_details_append"></div> --}}
                </div>

                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.staff_attendance.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.partials.date_picker')
        @include('pages.hrm.staff_attendance.new_script')
        {{-- @include('pages.hrm.staff_attendance.script') --}}
    @endsection
</x-default-layout>
