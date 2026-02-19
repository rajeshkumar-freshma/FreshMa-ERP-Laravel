<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Loan Product',
            'menu_1_link' => route('admin.loan-categories.index'),
            'menu_2' => 'Edit Loan Product',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Loan Product'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="loan_category_details" class="collapse show">
            <!--begin::Form-->
            <form id="loan_category_details_form" class="form" method="POST"
                action="{{ route('admin.loan-categories.update', @$loan_category->id) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Name') }}</label>
                                <input type="text" name="name" value="{{ old('name', @$loan_category->name) }}"
                                    class="form-control form-control-sm form-control-solid" id="name"
                                    placeholder="Enter Name" required />
                                @if ($errors->has('name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="short_name" class="form-label">{{ __('Short Name') }}</label>
                                <input type="text" name="short_name"
                                    value="{{ old('short_name', @$loan_category->short_name) }}"
                                    class="form-control form-control-sm form-control-solid" id="short_name"
                                    placeholder="Enter Short Name" />
                                @if ($errors->has('short_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('short_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="col-md-4">
                            <div class="mb-5">
                                <label for="defaultamount"
                                    class="form-label required">{{ __('Deafult Amount') }}</label>
                                <input type="text" name="defaultamount" value="{{ old('defaultamount') }}"
                                    class="form-control form-control-sm form-control-solid" id="defaultamount"
                                    placeholder="Enter Deafult Amount" required />
                                @if ($errors->has('defaultamount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('defaultamount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="mb-5">
                                <label for="min_amount" class="form-label required">{{ __('Min Amount') }}</label>
                                <input type="text" name="min_amount" value="{{ old('min_amount') }}"
                                    class="form-control form-control-sm form-control-solid" id="min_amount"
                                    placeholder="Enter Min Amount" required />
                                @if ($errors->has('min_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('min_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="amount" class="form-label required">{{ __('Amount') }}</label>
                                <input type="text" name="amount"
                                    value="{{ old('amount', @$loan_category->amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="amount"
                                    placeholder="Enter Amount" required />
                                @if ($errors->has('amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        {{-- <div class="col-md-4">
                            <div class="mb-5">
                                <label for="default_loan_term" class="form-label required">{{ __('Default Loan Term') }}</label>
                                <input type="text" name="default_loan_term" value="{{ old('default_loan_term') }}"
                                    class="form-control form-control-sm form-control-solid" id="default_loan_term"
                                    placeholder="Enter Default Loan Term" required />
                                @if ($errors->has('default_loan_term'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('default_loan_term') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="loan_tenure" class="form-label required">{{ __('Loan Tenure') }}</label>
                                <input type="text" name="loan_tenure"
                                    value="{{ old('loan_tenure', @$loan_category->loan_tenure) }}"
                                    class="form-control form-control-sm form-control-solid" id="loan_tenure"
                                    placeholder="Enter Loan Tenure" required />
                                @if ($errors->has('loan_tenure'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_tenure') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="loan_term" class="form-label required">{{ __('Loan Term') }}</label>
                                <input type="text" name="loan_term"
                                    value="{{ old('loan_term', @$loan_category->loan_term) }}"
                                    class="form-control form-control-sm form-control-solid" id="loan_term"
                                    placeholder="Enter Loan Term" required />
                                @if ($errors->has('loan_term'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_term') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="loan_term_method"
                                    class="form-label required">{{ __('Loan Term Method') }}</label>
                                <select name="loan_term_method" id="loan_term_method"
                                    aria-label="{{ __('Select Interest Type') }}" data-control="select2"
                                    data-placeholder="{{ __('Select interest Type..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    required>
                                    <option value="">{{ __('Select Interest Type..') }}</option>
                                    @foreach (config('app.loan_term_method') as $key => $value)
                                        <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                            {{ $value['value'] == old('loan_term_method', @$loan_category->loan_term_method) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="default_interest_rate" class="form-label">{{ __('Default Interest Rate') }}</label>
                                <input type="default_interest_rate" name="default_interest_rate" value="{{ old('default_interest_rate') }}"
                                    class="form-control form-control-sm form-control-solid" id="default_interest_rate"
                                    placeholder="Enter default_interest_rate" />
                                @if ($errors->has('Default Interest Rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('default_interest_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4">
                            <div class="mb-5">
                                <label for="min_interest_rate" class="form-label">{{ __('Min Interest Rate') }}</label>
                                <input type="min_interest_rate" name="min_interest_rate" value="{{ old('min_interest_rate') }}"
                                    class="form-control form-control-sm form-control-solid" id="min_interest_rate"
                                    placeholder="Enter min_interest_rate" />
                                @if ($errors->has('Min Interest Rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('min_interest_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="interest_rate"
                                    class="form-label required">{{ __('Interest Rate(%)') }}</label>
                                <input type="text" name="interest_rate"
                                    value="{{ old('interest_rate', @$loan_category->interest_rate) }}"
                                    class="form-control form-control-sm form-control-solid" id="interest_rate"
                                    placeholder="Enter interest_rate" required/>
                                @if ($errors->has('Interest Rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('interest_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
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
                                            {{ $value['value'] == old('interest_type', @$loan_category->interest_type) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                                            {{ $value['value'] == old('interest_frequency', @$loan_category->interest_frequency) ? 'selected' : '' }}>
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
                                            {{ $value['value'] == old('repayment_frequency', @$loan_category->repayment_frequency) ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="late_payment_penalty_rate"
                                    class="form-label required">{{ __('Late Payment Penalty Rate (%)') }}</label>
                                <input type="text" name="late_payment_penalty_rate"
                                    value="{{ old('late_payment_penalty_rate', @$loan_category->late_payment_penalty_rate) }}"
                                    class="form-control form-control-sm form-control-solid"
                                    id="late_payment_penalty_rate" placeholder="Enter Late Payment Penalties" required/>
                                @if ($errors->has('Late Payment Penalty Rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('late_payment_penalty_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="charges" class="form-label ">{{ __('Charges') }}</label>
                                <select name="charges[]" id="charges" aria-label="{{ __('Select Charges') }}"
                                    data-control="select2" data-placeholder="{{ __('Select Charges..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                    multiple>
                                    <option value="">{{ __('Select Charges..') }}</option>
                                    @foreach (@$loan_charge as $key => $value)
                                        <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                            {{ $value->id == old('charges', @$loan_category->charges_id) ? 'selected' : '' }}>
                                            {{ $value->name }}-Rs{{ $value->amount }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', @$loan_category->status) ? 'selected' : '' }}>
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
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="description" class="form-label">{{ __('description') }}</label>
                                <textarea name="description" class="form-control form-control-sm form-control-solid" id="description"
                                    placeholder="Enter description">{{ old('description', @$loan_category->description) }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
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
                    'back_url' => route('admin.warehouse.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.partials.state_city_script')
        @include('pages.partials.date_picker')
    @endsection
</x-default-layout>
