<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Payroll Type',
            'menu_1_link' => route('admin.pay-roll-type.index'),
            'menu_2' => 'Add Payroll Type',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Payroll Type'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="Payroll_type" class="collapse show">
            <!--begin::Form-->
            <form id="Payroll_type" class="form" method="POST" action="{{ route('admin.pay-roll-type.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body border-top p-9">
                    <!--begin::Card body-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('PayRoll Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="payroll_name" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" placeholder="Payroll Name" value="{{ old('payroll_name') }}" required />
                                @if ($errors->has('payroll_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payroll_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Details') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <textarea name="details" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" id="details" placeholder="Payroll Details">{{ old('details') }}</textarea>
                                @if ($errors->has('details'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('details') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>


                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Payroll Type') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-check-circle"></i>
                                    </span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="payroll_type" id="payroll_type" aria-label="{{ __('Payroll Leave Type') }}" data-control="select2" data-placeholder="{{ __('Payroll Leave Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Payroll Type..') }}</option>
                                            <option value="1" {{ old('payroll_type') == '1' ? 'selected' : '' }}>
                                                Add
                                            </option>
                                            <option value="2" {{ old('payroll_type') == '2' ? 'selected' : '' }}>
                                                Deduct
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('payroll_type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payroll_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>

                        <!--end::Col-->
                    </div>
                    <div class="row mb-0">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Is Loan') }}</label>
                        <!--begin::Label-->

                        <!--begin::Label-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                <input type="hidden" name="is_loan" value="0">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="is_loan" name="is_loan" value="1" {{ old('is_loan') ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_loan"></label>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-0">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Status') }}</label>
                        <!--begin::Label-->

                        <!--begin::Label-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                <input type="hidden" name="status" value="0">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }} />
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.pay-roll-type.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
