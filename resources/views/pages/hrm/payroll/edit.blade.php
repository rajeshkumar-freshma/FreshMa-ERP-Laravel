<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Payroll Setup',
            'menu_1_link' => route('admin.payroll.index'),
            'menu_2' => 'Edit Payroll Setup',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Payroll '])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="payroll" class="collapse show">
            <!--begin::Form-->
            <form id="payroll_form" class="form" method="POST" action="{{ route('admin.payroll.update', $payroll->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body border-top p-9">
                    <!--begin::Card body-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label onchange=""
                            class="col-lg-2 col-form-label required fw-bold fs-6">{{ __('Employee') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-2">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-user"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="employee_id" id="employee_id"
                                            aria-label="{{ __('Select Employee') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Employee..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            onchange="enableDropdown()">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $key => $details)
                                                <option value="{{ $details->id }}"
                                                    data-user-code="{{ $details->user_code }}"
                                                    {{ $details->id == $payroll->employee_id ? 'selected' : '' }}
                                                    class="optionGroup"
                                                    {{ $details->id != $payroll->employee_id ? 'disabled' : '' }}>
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

                                <!-- Add this hidden input field -->
                                {{-- <input type="hidden" name="selected_employee_id" value="{{ $payroll->employee_id }}"> --}}
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
                                            <select name="payroll_month" class="form-select form-select-sm"
                                                onchange="updateWorkingDays()">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}"
                                                        {{ sprintf('%02d', $i) == old('payroll_month', $payroll->month) ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <!-- Input for selecting the current year -->
                                            <select name="payroll_year" class="form-select form-select-sm"
                                                onchange="updateWorkingDays()">
                                                @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                    <option value="{{ $i }}"
                                                        {{ $i == old('payroll_year', $payroll->year) ? 'selected' : '' }}>
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

                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Loss of Pay Days') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!-- Replace the textarea with an input -->
                                <input type="text" name="loss_of_pay_days"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    id="LossOfPayDays" placeholder="Loss Of Pay Days"
                                    value="{{ old('loss_of_pay_days', $payroll->loss_of_pay_days) }}" required />
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
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Number of Working Days') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!-- Replace the textarea with an input -->
                                <input type="text" name="number_of_working_days"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    id="NumberOfWorkingDays" placeholder="Enter Number of Working Days"
                                    value="{{ old('number_of_working_days', $payroll->no_of_working_days) }}" readonly
                                    required />
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
                            Rs:<label id="grossSalaryLabel" name="gross_salary"
                                class="col-form-label fw-bold fs-6"></label>
                            <input type="hidden" name="gross_salary" id="grossSalaryInput"
                                value="{{ $payroll->gross_salary ? $payroll->gross_salary : '' }}">
                        </div>
                    </div>
                    <div class="row  mb-6 earning_deduction_append">
                        <!-- Add a label to display gross salary -->
                        <!-- Earnings Card -->
                        {{-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title bg-light p-2">{{ __('Earnings') }}</h5>
                                    <div class="row" id="payrollEarningContainer">
                                        @forelse ($earnings as $earning)
                                            <div class="row mb-2 remove">
                                                <label class="col-md-3 col-form-label text-success">
                                                    {{ ucfirst($earning->payroll_type->name) }}
                                                </label>
                                                <input type="hidden" name="payroll_data[{{ $earning->payroll_type_id }}][payroll_type_id][]" value="{{ $earning->payroll_type_id }}">
                                                <div class="col-md-5">
                                                    <input name="payroll_data[{{ $earning->payroll_type_id }}][amount][]" type="text" class="form-control form-control-sm amount-input" placeholder="Enter Amount" id="amount{{ $earning->payroll_type_id }}" value="{{ $earning->amount }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="input-text border-0 text-danger" onclick="removeRow(this)">
                                                        <i class="fas fa-times fs-4"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        @empty
                                            <p>No earnings found.</p>
                                        @endforelse
                                    </div>

                                    <div class="row">
                                        <div class="row-lg-1">
                                            <div class="row mb-2">
                                                <div class="row-12">
                                                    <button type="button" class="btn btn-success btn-sm" onclick="addPayroll(1)">
                                                        Add <span><i class="fas fa-plus"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ... existing code ... -->
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title bg-light p-2">{{ __('Deductions') }}</h5>
                                    <div class="row" id="payrollDeductionContainer">
                                        @forelse ($deductions as $deduction)
                                            <div class="row mb-2 remove">
                                                <label class="col-md-3 col-form-label text-danger">
                                                    {{ ucfirst($deduction->payroll_type->name) }}
                                                </label>
                                                <input type="hidden" name="payroll_data[{{ $deduction->payroll_type_id }}][payroll_type_id]" value="{{ $deduction->payroll_type_id }}">
                                                <div class="col-md-5">
                                                    <input name="payroll_data[{{ $deduction->payroll_type_id }}][amount]" type="text" class="form-control form-control-sm amount-input" placeholder="Enter Amount" id="amount{{ $deduction->payroll_type_id }}" value="{{ $deduction->amount ? $deduction->amount : '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="input-text border- text-danger" onclick="removeRow(this)">
                                                        <i class="fas fa-times fs-4"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        @empty
                                            <p>No earnings found.</p>
                                        @endforelse
                                    </div>
                                    <div class="row">
                                        <div class="row-lg-1">
                                            <div class="row mb-2">
                                                <div class="row-12">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="addPayroll(0)">
                                                        Add <span><i class="fas fa-plus"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ... existing code ... -->
                                </div>
                            </div>
                        </div> --}}
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
                                    <div class="overflow-hidden flex-grow-1">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <div
                                                class="form-check form-check-solid form-check-custom form-switch fv-row">
                                                <input type="hidden" name="status" value="0">
                                                <input class="form-check-input w-45px h-30px" type="checkbox"
                                                    id="status" name="status" value="1"
                                                    {{ old('status', $payroll->status) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="status"></label>
                                            </div>
                                        </div>
                                        @if ($errors->has('status'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--end::Row-->
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Remarks') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <textarea name="remarks" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" id="Remarks"
                                    placeholder="Payroll remarks">{{ old('remarks', $payroll->remarks) }}</textarea>
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
        <script>
            $(document).ready(function() {
                enableDropdown();

                // Call enableDropdown on page load if you want to initialize based on the current selection
                $(document).on('DOMContentLoaded', function() {
                    enableDropdown();
                });

                // Use event delegation for the change event
                $(document).on('change', '#employee_id', function() {
                    enableDropdown();
                });

                function enableDropdown() {
                    var selectedEmployeeId = $('#employee_id').val();
                    var options = $('#employee_id option');

                    options.each(function(index, option) {
                        if ($(option).val() !== selectedEmployeeId && $(option).val() !== '') {
                            $(option).prop('disabled', true);
                        } else {
                            $(option).prop('disabled', false);
                        }
                    });
                }
            });
        </script>
    @endsection
</x-default-layout>
