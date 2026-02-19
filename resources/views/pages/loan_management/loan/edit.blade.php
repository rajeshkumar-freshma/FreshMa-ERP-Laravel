<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Apply Loan',
            'menu_1_link' => route('admin.loans.index'),
            'menu_2' => 'Edit Apply Loan',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Loan'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="loan_details" class="collapse show">
            <!--begin::Form-->
            <form id="loan_details_form" class="form" method="POST" action="{{ route('admin.loans.update', @$loans->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        {{-- <div class="col-md-12">
                            <div class="mb-8 mt-3">
                                <h2 class="fw-bold">{{ __('Borrower Details') }}</h2>
                            </div>
                        </div> --}}
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Borrower Details</h2>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="mb-5">
                                <label for="employee_id" class="required form-label">{{ __('Employee') }}</label>
                                <select name="employee_id" id="employee_id" aria-label="{{ __('Select Employee') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Employee..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                    <option value="">Select Employee</option>
                                    @foreach (@$employees as $key => $details)
                                        <option value="{{ $details->id }}"
                                            {{ $details->id == old('employee_id',$loans->employee_id) ? 'selected' : '' }}
                                            class="optionGroup">
                                            {{ ucFirst($details->first_name) }}{{ ucFirst($details->last_name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('employee_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="loan_type_id" class="required form-label">{{ __('Loan Type') }}</label>
                                <select name="loan_type_id" id="loan_type_id" aria-label="{{ __('Select Loan Type') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Loan Type..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    onchange="hideFormEmployee()">
                                    <option value="">Select Loan From</option>
                                    @foreach (config('app.loan_type') as $key => $details)
                                        <option value="{{ $details['value'] }}"
                                            {{ $details['value'] == old('loan_type_id', @$loans->loan_type_id) ? 'selected' : '' }}
                                            class="optionGroup">
                                            {{ $details['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('loan_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="loan_code" class="form-label">{{ __('Loan Code') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    id="loan_code" name="loan_code" value="{{ old('loan_code', @$loans->loan_code) }}"
                                    placeholder="Loan Code" readonly />
                                @if ($errors->has('loan_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4" id="bank_id_div">
                            <div class="mb-5">
                                <label for="bank_id" class="required form-label">{{ __('Bank') }}</label>
                                <div class="input-group">
                                    <select name="bank_id" id="bank_id" aria-label="{{ __('Select Bank') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Bank..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">Select Bank</option>
                                        <p>{{ @$loan_bank_account }}</p>
                                        @foreach (@$loan_bank_account as $key => $details)
                                            <option value="{{ $details->id }}"
                                                {{ $details->id == old('bank_id', @$loans->bank_id) ? 'selected' : '' }}
                                                class="optionGroup">
                                                {{ ucFirst($details->bank_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('bank_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('bank_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success btn-sm" onclick="confirmBankDetails()">
                                    Add Bank<span><i class="fas fa-plus"></i></span>
                                </button>
                            </div>
                        </div>



                        <div class="col-md-6" id="employee_id_div">
                            <div class="mb-5">
                                <label for="employee_id" class="form-label">{{ __('Employee') }}</label>
                                <select name="employee_id" id="employee_id" aria-label="{{ __('Select Employee') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Employee..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                    <option value="">Select Employee</option>
                                    @foreach (@$employees as $key => $details)
                                        <option value="{{ $details->id }}"
                                            {{ $details->id == old('employee_id', $loans->employee_id) ? 'selected' : '' }}
                                            class="optionGroup">
                                            {{ ucFirst($details->first_name) }}{{ ucFirst($details->last_name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('employee_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="short_name" class="form-label required">{{ __('Loan Product') }}</label>
                                <select name="loan_category_id" id="loan_category_id"
                                    aria-label="{{ __('Select Loan Product') }}" data-control="select2"
                                    data-placeholder="{{ __('Select Loan Product..') }}"
                                    class="form-select form-select-sm form-select-solid " data-allow-clear="true"
                                    onchange="getLoanCategoryDetails()">
                                    <option value="">Select Loan Product</option>
                                    @foreach (@$product_categories as $key => $details)
                                        <option value="{{ $details->id }}"
                                            {{ $details->id == old('loan_category_id', $loans->loan_category_id) ? 'selected' : '' }}
                                            class="optionGroup">
                                            {{ ucFirst($details->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('loan_category_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="phone_number"
                                    class="form-label required">{{ __('Phone Number') }}</label>
                                <input type="text" name="phone_number"
                                    value="{{ old('phone_number', $loans->phone_number) }}"
                                    class="form-control form-control-sm form-control-solid" id="phone_number"
                                    placeholder="Enter Phone number" required />
                                @if ($errors->has('phone_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="applied_amount"
                                    class="form-label required">{{ __('Applied Amount') }}</label>
                                <input type="text" name="applied_amount"
                                    value="{{ old('applied_amount', $loans->applied_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="applied_amount"
                                    placeholder="Enter Applied Amount" required />
                                @if ($errors->has('applied_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('applied_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="applied_on" class="form-label required">{{ __('Applied On') }}</label>
                                <div class="input-group">
                                    <input id="applied_on" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="applied_on" placeholder="Applied On"
                                        value="{{ old('applied_on', $loans->applied_on) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('applied_on'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('applied_on') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="deduct_form_salary"
                                    class="form-label required">{{ __('Pay Interest From Salary') }}</label>
                                <select name="deduct_form_salary" id="deduct_form_salary"
                                    aria-label="{{ __('Select Interest Type..') }}" data-control="select2"
                                    data-placeholder="{{ __('Select interest Type..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    required>
                                    <option value="">{{ __('Select Deduct Type..') }}</option>
                                    <option value="1"
                                        {{ old('deduct_form_salary', $loans->deduct_form_salary) == '1' ? 'selected' : '' }}>
                                        {{ __('Yes') }}
                                    </option>
                                    <option value="2"
                                        {{ old('deduct_form_salary', $loans->deduct_form_salary) == '2' ? 'selected' : '' }}>
                                        {{ __('No') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="guarantors" class=" form-label">{{ __('Guarantor') }}</label>
                                <select name="guarantors" id="guarantors" aria-label="{{ __('Select Guarantor') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Guarantor..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                    <option value="">Select Guarantor</option>
                                    @foreach (@$employees as $key => $details)
                                        <option value="{{ $details->id }}"
                                            {{ $details->id == old('guarantors', $loans->guarantors) ? 'selected' : '' }}
                                            class="optionGroup">
                                            {{ ucFirst($details->first_name) }}{{ ucFirst($details->last_name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('guarantors'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('guarantors') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                                <textarea name="remarks" class="form-control form-control-sm form-control-solid" id="remarks"
                                    placeholder="Enter Remarks">{{ old('remarks', $loans->description) }}</textarea>
                                @if ($errors->has('remarks'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('remarks') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="mb-8 mt-3">
                                <h2 class="fw-bold">{{ __('Loan Details') }}</h2>
                            </div>
                        </div> --}}
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Loan Details</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="principal_amount"
                                    class="form-label required">{{ __('Principal Amount') }}</label>
                                <input type="text" name="principal_amount"
                                    value="{{ old('principal_amount', $loans->principal_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="principal_amount"
                                    placeholder="Enter Principal Amount" required />
                                @if ($errors->has('principal_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('principal_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="first_payment_date"
                                    class="form-label required">{{ __('First Payment Date') }}</label>
                                <div class="input-group">
                                    <input id="first_payment_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="first_payment_date" placeholder="First Payment Date"
                                        value="{{ old('first_payment_date', $loans->first_payment_date) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('first_payment_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_payment_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="loan_tenure" class="form-label required">{{ __('Loan Tenure') }}</label>
                                <input type="text" name="loan_tenure"
                                    value="{{ old('loan_tenure', $loans->loan_tenure) }}"
                                    class="form-control form-control-sm form-control-solid" id="loan_tenure"
                                    placeholder="Enter Loan Tenure" required />
                                @if ($errors->has('loan_tenure'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_tenure') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="loan_term" class="form-label required">{{ __('Loan Term') }}</label>
                                <input type="text" name="loan_term"
                                    value="{{ old('loan_term', $loans->loan_term) }}"
                                    class="form-control form-control-sm form-control-solid" id="loan_term"
                                    placeholder="Enter Loan Term" required />
                                @if ($errors->has('loan_term'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_term') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="interest_rate"
                                    class="form-label required">{{ __('Interest Rate (%)') }}</label>
                                <input type="text" name="interest_rate"
                                    value="{{ old('interest_rate', $loans->interest_rate) }}"
                                    class="form-control form-control-sm form-control-solid" id="interest_rate"
                                    placeholder="Enter interest_rate" />
                                @if ($errors->has('Interest Rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('interest_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="mb-5">
                                <label for="interest_type"
                                    class="form-label required">{{ __('Interest Type') }}</label>
                                <select name="interest_type" id="interest_type"
                                    aria-label="{{ __('Select Interest Type') }}" data-control="select2"
                                    data-placeholder="{{ __('Select interest Type..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    required>
                                    <option value="">{{ __('Select Interest Type..') }}</option>
                                    @foreach (config('app.interest_types') as $key => $value)
                                        <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                            {{ $value['value'] == old('interest_type',$loans->interest_type) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="interest_frequency"
                                    class="form-label required">{{ __('Interest Frequency') }}</label>
                                <select name="interest_frequency" id="interest_frequency"
                                    aria-label="{{ __('Select Interest Frequency') }}" data-control="select2"
                                    data-placeholder="{{ __('Select interest Frequency..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    required>
                                    <option value="">{{ __('Select Interest Frequency..') }}</option>
                                    @foreach (config('app.interest_frequency') as $key => $value)
                                        <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                            {{ $value['value'] == old('interest_frequency', $loans->interest_frequency) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="repayment_frequency"
                                    class="form-label required">{{ __('Repayment Frequency') }}</label>
                                <select name="repayment_frequency" id="repayment_frequency"
                                    aria-label="{{ __('Select Repayment Frequency') }}" data-control="select2"
                                    data-placeholder="{{ __('Select Repayment Frequency..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    required>
                                    <option value="">{{ __('Select Repayment Frequency..') }}</option>
                                    @foreach (config('app.repayment_frequency') as $key => $value)
                                        <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                            {{ $value['value'] == old('repayment_frequency', $loans->repayment_frequency) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="repayment_amount"
                                    class="form-label required">{{ __('Repayment Amount Per Month') }}</label>
                                <input type="text" name="repayment_amount"
                                    value="{{ old('repayment_amount', $loans->repayment_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="repayment_amount"
                                    placeholder="Enter Repayment Amount Per Month.." required readonly />
                                @if ($errors->has('repayment_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('repayment_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="late_payment_penalty_rate"
                                    class="form-label required">{{ __('Late Payment Penalty Rate(%)') }}</label>
                                <input type="text" name="late_payment_penalty_rate"
                                    value="{{ old('late_payment_penalty_rate', $loans->late_payment_penalty_rate) }}"
                                    class="form-control form-control-sm form-control-solid"
                                    id="late_payment_penalty_rate" placeholder="Enter Late Payment Penalties" />
                                @if ($errors->has('Late Payment Penalty Rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('late_payment_penalty_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="description" class="form-label">{{ __('description') }}</label>
                                <textarea name="description" class="form-control form-control-sm form-control-solid" id="description"
                                    placeholder="Enter description">{{ old('description', $loans->description) }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-header">
                            <div class="card-title">
                                <h2>Document/Attachment upload</h2>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-5">
                                <!--begin::Dropzone-->
                                {{-- <input type="file" class="form-control" id="documents/attachments"
                                    name="documents/attachments[]" multiple> --}}
                                <div class="input-group">
                                    <input id="documents" type="file"
                                        class="form-control form-control-sm form-control-solid" name="documents[]"
                                        placeholder="Document/Attachments"
                                        value="{{ old('documents', $loans->documents) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-file"></i>
                                    </span>
                                    <!--end::Dropzone-->
                                    @if ($errors->has('documents'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('documents') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Disburse Details</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="disburse_method"
                                    class="required form-label">{{ __('Disburse Method') }}</label>
                                <select name="disburse_method" id="disburse_method"
                                    aria-label="{{ __('Select Disburse Method') }}" data-control="select2"
                                    data-placeholder="{{ __('Select Disburse Method..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                    <option value="">Select Disburse Method</option>
                                    @foreach (config('app.disburse_method') as $key => $value)
                                        <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                            {{ $value['value'] == old('disburse_method', $loans->disburse_method) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('disburse_method'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('disburse_method') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="distributed_date"
                                    class="form-label required">{{ __('Distributed Date') }}</label>
                                <div class="input-group">
                                    <input id="distributed_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="distributed_date" placeholder="Distributed Date"
                                        value="{{ old('distributed_date', $loans->distributed_date) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('distributed_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('distributed_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="loan_status" class="form-label required">{{ __('Loan Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="loan_status" id="loan_status"
                                            aria-label="{{ __('Loan Status') }}" data-control="select2"
                                            data-placeholder="{{ __('Loan Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Loan Status..') }}</option>
                                            @foreach (config('app.loan_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('loan_status', $loans->loan_status) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('loan_status'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="disburse_notes" class="form-label">{{ __('Disburse Notes') }}</label>
                                <textarea name="disburse_notes" class="form-control form-control-sm form-control-solid" id="disburse_notes"
                                    placeholder="Enter Disburse Notes">{{ old('disburse_notes', $loans->disburse_notes) }}</textarea>
                                @if ($errors->has('disburse_notes'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('disburse_notes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.loans.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.loan_management.loan.script')
    @endsection
</x-default-layout>
