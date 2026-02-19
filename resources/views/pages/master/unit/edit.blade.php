<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Unit',
            'menu_1_link' => route('admin.unit.index'),
            'menu_2' => 'Edit Unit',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Unit'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="unit" class="collapse show">
            <!--begin::Form-->
            <form id="unit_form" class="form" method="POST" action="{{ route('admin.unit.update', $unit->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Unit Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="unit_name"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Unit Name" value="{{ old('unit_name', $unit->unit_name) }}" required />
                                @if ($errors->has('unit_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('unit_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Unit Short Code') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="unit_short_code"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Unit Short Code"
                                    value="{{ old('unit_short_code', $unit->unit_short_code) }}" required />
                                @if ($errors->has('unit_short_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('unit_short_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Unit Base Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="base_unit"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Unit Base Name" value="{{ old('base_unit', $unit->base_unit) }}"
                                    required />
                                @if ($errors->has('base_unit'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('base_unit') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Allow Decimal') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="allow_decimal"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Allow Decimal"
                                    value="{{ old('allow_decimal', $unit->allow_decimal) }}" required />
                                @if ($errors->has('allow_decimal'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('allow_decimal') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Operator') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="operator" id="operator" aria-label="{{ __('Select Operator') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Operator..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Operator..') }}</option>
                                            @foreach (config('app.unit_oprators') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('operator', $unit->operator) ? 'selected' : '' }}>
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
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Operation Value') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="operation_value"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Operation Value"
                                    value="{{ old('operation_value', $unit->operation_value) }}" required />
                                @if ($errors->has('operation_value'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('operation_value') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-0">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Status') }}</label>
                        <!--begin::Label-->

                        <!--begin::Label-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                <input type="hidden" name="status" value="0">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="status"
                                    name="status" value="1"
                                    {{ old('status', $unit->status) ? 'checked' : '' }} />
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>

                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Default') }}</label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                <input type="hidden" name="default" value="0">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="status"
                                    name="default" value="1" {{ old('status', $unit->default) ? 'checked' : '' }} />
                                <label class="form-check-label" for="default"></label>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => false,
                    'back_url' => route('admin.unit.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
