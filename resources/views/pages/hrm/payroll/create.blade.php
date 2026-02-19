<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Payroll Setup',
            'menu_1_link' => route('admin.payroll.index'),
            'menu_2' => 'Add Payroll Setup',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Payroll '])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="payroll" class="collapse show">
            <!--begin::Form-->
            <form id="payroll_form" class="form" method="POST" action="{{ route('admin.payroll.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body border-top p-9">
                    <!--begin::Card body-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label onchange="getEmployees()" class="col-lg-2 col-form-label required fw-bold fs-6">{{ __('Employee') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-3">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-user"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="employee_id" id="employee_id" aria-label="{{ __('Select Employee') }}" data-control="select2" data-placeholder="{{ __('Select Employee..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">Select Employee</option>
                                            @foreach ($employee as $key => $details)
                                                <p id="user_code">{{ $details->user_code }}</p>
                                                <option value="{{ $details->id }}" data-user-code="{{ $details->user_code }}" {{ $details->id == old('employee_id') ? 'selected' : '' }} class="optionGroup">
                                                    {{ ucFirst($details->first_name) }}{{ ucFirst($details->last_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('payment_category'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('payment_category') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if ($errors->has('employee_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="row" id="payrollMonthContainer">
                                    <!-- Loop for Deductions -->
                                    <div class="row mb-2">
                                        <!-- ... Deductions content ... -->
                                        <div class="col-md-3">
                                            <!-- Input for selecting the current month -->
                                            <select name="payroll_month" class="form-select form-select-sm" onchange="updateWorkingDays()">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}" {{ sprintf('%02d', $i) == date('m') ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor

                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <!-- Input for selecting the current year -->
                                            <select name="payroll_year" class="form-select form-select-sm" onchange="updateWorkingDays()">
                                                @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <b id="show_user_code"></b>
                                            <!--end::Col-->
                                        </div>
                                    </div>
                                </div>

                                @if ($errors->has('year'))
                                    <!-- Error message for Deductions -->
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('year') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Los of Pay Days') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!-- Replace the textarea with an input -->
                                    <input type="text" name="loss_of_pay_days" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" id="LossOfPayDays" placeholder="Loss Of Pay Days" value="{{ old('loss_of_pay_days') }}" required/>
                                    <!-- Display validation error message if exists -->
                                    @if ($errors->has('loss_of_pay_days'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('loss_of_pay_days') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Number of Working Days') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!-- Replace the textarea with an input -->
                                    <input type="text" name="number_of_working_days" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" id="NumberOfWorkingDays" placeholder="Enter Number of Working Days" value="{{ old('number_of_working_days') }}" required/>
                                    <!-- Display validation error message if exists -->
                                    @if ($errors->has('number_of_working_days'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('number_of_working_days') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Col-->
                        <div class="row mb-6">
                            <label class="col-md-2 col-form-label fw-bold fs-6">Gross Salary:</label>
                            <div class="col-md-3">
                                Rs:<label id="grossSalaryLabel" name="gross_salary" class="col-form-label fw-bold fs-6"></label>
                                <input type="hidden" name="gross_salary" id="grossSalaryInput">
                            </div>
                        </div>

                        <div class="row earning_deduction_append">
                            <!-- Add a label to display gross salary -->
                            <!-- Earnings Card -->
                        </div>

                        <div class="row">
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Status') }}</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <div class="overflow-hidden flex-grow-1">
                                                <div class="col-lg-8 d-flex align-items-center">
                                                    <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                                        <input type="hidden" name="status" value="0">
                                                        <input class="form-check-input w-45px h-30px" type="checkbox" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }} />
                                                        <label class="form-check-label" for="status"></label>
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
                                    <!--end::Row-->
                                </div>
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Remarks') }}</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <textarea name="remarks" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" id="Remarks" placeholder="Payroll remarks">{{ old('remarks') }}</textarea>
                                            @if ($errors->has('remarks'))
                                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('remarks') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>

                                <!--end::Col-->
                            </div>
                        </div>
                        <!--begin::Input group-->
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    @include('pages.partials.form_footer', [
                        'is_save' => true,
                        'back_url' => route('admin.payroll.index'),
                    ])
                    <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
        @include('pages.hrm.payroll.script')
    @endsection
</x-default-layout>
