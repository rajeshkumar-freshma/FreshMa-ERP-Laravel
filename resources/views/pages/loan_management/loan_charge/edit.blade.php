<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Loan Charges',
            'menu_1_link' => route('admin.loan-charges.index'),
            'menu_2' => 'Edit Loan Charges',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Loan Charges'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="loan_charges" class="collapse show">
            <!--begin::Form-->
            <form id="loan_charges_form" class="form" method="POST" action="{{ route('admin.loan-charges.update',$loanCharges->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Charges</h2>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="name"
                                    class="form-label required">{{ __(' Name') }}</label>
                                <input type="text" name="name" value="{{ old('name',$loanCharges->name) }}"
                                    class="form-control form-control-sm form-control-solid" id="name"
                                    placeholder="Enter  Name.." required />
                                @if ($errors->has('name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="short_name"
                                    class="form-label required">{{ __('Short Name') }}</label>
                                <input type="text" name="short_name" value="{{ old('short_name',$loanCharges->short_name) }}"
                                    class="form-control form-control-sm form-control-solid" id="short_name"
                                    placeholder="Enter Short Name.." required />
                                @if ($errors->has('short_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('short_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="amount"
                                    class="form-label required">{{ __(' Amount') }}</label>
                                <input type="text" name="amount" value="{{ old('amount',$loanCharges->amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="amount"
                                    placeholder="Enter  Amount.." required />
                                @if ($errors->has('amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status',$loanCharges->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
