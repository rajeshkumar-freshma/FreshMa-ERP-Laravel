<x-default-layout>
        <!--begin::Card-->
        @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Leave',
            'menu_1_link' => route('admin.leave.index'),
            'menu_2' => 'Add Leave',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Leave'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="leave" class="collapse show">
            <!--begin::Form-->
            <form id="leave_form" class="form" method="POST" action="{{ route('admin.leave.store') }}"
                enctype="multipart/form-data">
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
                                            data-allow-clear="true" required>
                                            <option value="">Select Employee</option>
                                            @foreach ($employee as $key => $details)
                                                <option value="{{ $details->id }}"
                                                    {{ $details->id == old('employee_id') ? 'selected' : '' }}
                                                    class="optionGroup">
                                                    {{ ucFirst($details->first_name) }}{{ ucFirst($details->last_name) }}
                                                </option>
                                            @endforeach
                                        </select>
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
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Leave Type') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-calendar-alt"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="leave_type" id="leave_type"
                                            aria-label="{{ __('Select Leave Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Leave Type..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">Select Leave Type</option>
                                            @foreach ($leave_type as $key => $details)
                                                <option value="{{ $details->id }}"
                                                    {{ $details->id == old('leave_type') ? 'selected' : '' }}
                                                    class="optionGroup">
                                                    {{ ucFirst($details->name) }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <!-- Plus button -->
                                    <span class="input-group-text border-0" onclick="toggleAdditionalFields()">
                                        <i class="fas fa-plus-circle fs-4"></i>
                                    </span>
                                </div>
                                <!-- Additional fields (hidden by default) -->
                                {{-- <div id="additionalFields" class="modal-body" style="display: none; width: 100%;">
                                    <label for="po_item" class="form-label">{{ __('Create Leave Type') }}</label>
                                    <input type="text" name="leave_type_name"
                                        class="form-control form-control-sm form-control-solid mt-2" id="leave_type_name"
                                        placeholder="Leave Name" />
                                    <input type="text" name="leave_type_status" value="1" hidden
                                        class="form-control form-control-sm form-control-solid mt-2" id="leave_type_status" />
                                    <button type="button" class="btn btn-sm btn-success me-2" id="item_details_submit"
                                        name="submission_type" onclick="leaveTypeStored()">
                                        Create
                                    </button>
                                </div> --}}
                                <div class="modal fade" id="additionalFieldsModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    {{ __('Add Leave Type') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="text" name="leave_type_name"
                                                    class="form-control form-control-sm form-control-solid mt-2"
                                                    id="leave_type_name" placeholder="Leave Name" />
                                                <input type="text" name="leave_type_status" value="1" hidden
                                                    class="form-control form-control-sm form-control-solid mt-2"
                                                    id="leave_type_status" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success"
                                                    onclick="leaveTypeStored()">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                @if ($errors->has('leave_type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('leave_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Start Date') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <div class="input-group">
                                        <!-- Replace select with date picker input -->
                                        <input id="start_date" type="text" required
                                            class="form-control form-control-solid fsh_flat_datepicker"
                                            name="start_date" placeholder="Enter Start Date" />
                                    </div>
                                </div>
                                @if ($errors->has('start_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>

                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('End Date') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-calendar"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <!-- Replace select with date picker input -->
                                        <input id="end_date" type="text" required
                                            class="form-control form-control-solid fsh_flat_datepicker"
                                            name="end_date" placeholder="Enter End Date" />
                                    </div>
                                </div>
                                @if ($errors->has('end_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>

                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label  required fw-bold fs-6">{{ __('Reason') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-comment"></i>
                                    </span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <!-- Replace select with date picker input -->
                                        <textarea name="reason" class="form-control form-control-sm form-control-solid" id="reason" required
                                            placeholder="Enter Reason">{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                                @if ($errors->has('reason'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Remark') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-comment"></i>
                                    </span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <!-- Replace select with date picker input -->
                                        <textarea name="remark" class="form-control form-control-sm form-control-solid" id="remark"
                                            placeholder="Enter Remark">{{ old('remark') }}</textarea>
                                    </div>
                                </div>
                                @if ($errors->has('remark'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('remark') }}</strong>
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
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Approved status') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-check-circle"></i>
                                    </span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="approved_status" id="approved_status"
                                            aria-label="{{ __('Select Leave Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Leave Type..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.approved_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('approved_status') ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('approved_status'))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('approved_status') }}</strong>
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
                    <div class="row mb-0">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Is Half Day') }}</label>
                        <!--begin::Label-->

                        <!--begin::Label-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                <input type="hidden" name="is_half_day" value="0">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="is_half_day" required
                                    name="is_half_day" value="1" {{ old('is_half_day', 1) ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_half_day"></label>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.leave.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
        @include('pages.hrm.leave.script')
    @endsection
</x-default-layout>
