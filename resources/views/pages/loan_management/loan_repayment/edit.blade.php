<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Re Payments',
            'menu_1_link' => route('admin.loan-repayment.index'),
            'menu_2' => 'Edit Re Payments',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Loan Repayment'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="loan_repayment" class="collapse show">
            <!--begin::Form-->
            <form id="loan_repayment_form" class="form" method="POST" action="{{ route('admin.loan-repayment.update',$loanRepayment->id) }}"
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
                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="loan_id"
                                    class="required form-label">{{ __('Loan Id') }}</label>
                                <select name="loan_id" id="loan_id"
                                    aria-label="{{ __('Select Loan Id') }}" data-control="select2"
                                    data-placeholder="{{ __('Select Loan Id..') }}"
                                    class="form-select form-select-sm form-select-solid" data-allow-clear="true" onchange="getLoanDetails()" aria-readonly="true">
                                    <option value="">Select Loan Id</option>
                                    @foreach (@$loanTransactions as $key => $details)
                                        <option value="{{ $details->id }}"
                                            {{ $details->id == old('loan_id',$loanRepayment->loan_id) ? 'selected' : '' }}
                                            class="optionGroup">
                                            {{ $details->loan_code }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('loan_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="payment_date" class="form-label required">{{ __('Instalment Date') }}</label>
                                <div class="input-group">
                                    <input id="payment_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="payment_date" placeholder="Instalment Date" value="{{ old('payment_date',$loanRepayment->payment_date) }}" />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('payment_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="instalment_amount"
                                    class="form-label required">{{ __('Instalment Amount') }}</label>
                                <input type="text" name="instalment_amount" value="{{ old('instalment_amount',$loanRepayment->instalment_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="instalment_amount"
                                    placeholder="Enter Instalment Amount.." required readonly/>
                                @if ($errors->has('instalment_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('instalment_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="pay_amount"
                                    class="form-label required">{{ __('Pay Amount') }}</label>
                                <input type="text" name="pay_amount" value="{{ old('pay_amount',$loanRepayment->pay_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="pay_amount"
                                    placeholder="Enter Pay Amount.." required />
                                @if ($errors->has('pay_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pay_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="due_amount"
                                    class="form-label required">{{ __('Due Amount') }}</label>
                                <input type="text" name="due_amount" value="{{ old('due_amount',$loanRepayment->due_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="due_amount"
                                    placeholder="Enter Due Amount.." required readonly/>
                                @if ($errors->has('due_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('due_amount') }}</strong>
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
                    'back_url' => route('admin.loan-repayment.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.loan_management.loan_repayment.script')
    @endsection
</x-default-layout>
