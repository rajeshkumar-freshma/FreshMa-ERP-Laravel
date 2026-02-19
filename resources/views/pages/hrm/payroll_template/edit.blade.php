<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Payroll Template',
            'menu_1_link' => route('admin.pay-roll-template.index'),
            'menu_2' => 'Edit Payroll Template',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Payroll Template'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="payroll_template" class="collapse show">
            <!--begin::Form-->
            <form id="payroll_template_form" class="form" method="POST" action="{{ route('admin.pay-roll-template.update',$pay_roll_template->id) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body border-top p-9">
                    <!--begin::Card body-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Employee') }}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-user"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="employee_id" id="employee_id"
                                            aria-label="{{ __('Select Employee') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Employee..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">Select Employee</option>
                                            @foreach ($employee as $key => $details)
                                                <option value="{{ $details->id }}"
                                                    {{ $details->id == $pay_roll_template->employee_id ? 'selected' : '' }}
                                                    class="optionGroup">
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

                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('PayRoll Type') }}</label>
                        <div class="col-lg-8">
                            <div class="row" id="payrollTypesContainer">
                                @foreach ($payroll_types as $key => $details)
                                <div class="row mb-2">
                                    <label class="col-md-3 col-form-label {{ $details->payroll_types == 1 ? 'text-success' : 'text-danger' }}">
                                        {{ ucfirst($details->name) }}
                                    </label>
                                    <input type="hidden" name="payroll_data[{{ $details->id }}][payroll_type_id]" value="{{ $details->id }}">
                                    <div class="col-md-3">
                                        <input name="payroll_data[{{ $details->id }}][amount]" type="text" class="form-control" placeholder="Enter Amount"
                                            value="{{ isset($stored_payroll_data[$details->id]['amount']) ? $stored_payroll_data[$details->id]['amount'] : '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <span class="input-text border-0 text-danger" onclick="removeRow(this)">
                                            <i class="fas fa-times fs-4"></i>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                            </div>

                            @if ($errors->has('payroll_data'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payroll_data') }}</strong>
                                </span>
                            @endif
                        </div>
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
                                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                                <input type="hidden" name="status" value="0">
                                                <input class="form-check-input w-45px h-30px" type="checkbox" id="status" name="status" value="1" {{ $pay_roll_template->status == 1 ? 'checked' : '' }}>
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

                        <!--end::Col-->
                    </div>




                    <!--begin::Input group-->
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.pay-roll-template.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
        {{-- @include('pages.hrm.leave.script') --}}
            <script>
                function removeRow(button) {
                    // Find the parent row and remove it
                    var row = button.closest('.row');
                    row.remove();
                }
            </script>
    @endsection
</x-default-layout>
