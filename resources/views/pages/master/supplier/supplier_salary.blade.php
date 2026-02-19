<!--begin::Tab pane-->

<div class="tab-pane fade" id="add_supplier_salary_detail" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <!--begin::Meta options-->
        <div class="card card-flush py-4">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="mb-5">
                    <label for="salary_type" class="form-label required">{{ __('Salary Type') }}</label>
                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fa fa-money-bill"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="salary_type" id="salary-type-dropdown"
                                aria-label="{{ __('Select Salary Type') }}" data-control="select2"
                                data-placeholder="{{ __('Select Salary Type..') }}"
                                class="form-select form-select-sm form-select-solid" data-allow-clear="true" >
                                <option value="">{{ __('Select Salary Type..') }}</option>
                                @foreach (config('app.salary_type') as $key => $value)
                                    <option data-kt-flag="{{ $key }}" value="{{ $key }}"
                                        {{ $key == old('salary_type', @$data->salary_type) ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($errors->has('salary_type'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('salary_type') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="amount_type" class="form-label required">{{ __('Type') }}</label>

                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fa fa-money-bill"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="amount_type" id="amount-type-dropdown" aria-label="{{ __('Select Type') }}"
                                data-control="select2" data-placeholder="{{ __('Select Type..') }}"
                                class="form-select form-select-sm form-select-solid" data-allow-clear="true" >
                                <option value="">{{ __('Select Type..') }}</option>
                                @foreach (config('app.amount_type') as $key => $value)
                                    <option data-kt-flag="{{ $key }}" value="{{ $key }}"
                                        {{ $key == old('amount_type', isset($data->amount_type) ? @$data->amount_type : 1) ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($errors->has('amount_type'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('amount_type') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5 amountDiv d-none">
                    <label for="amount" class="form-label required">{{ __('Amount') }}</label>
                    <input type="text" name="amount" value="{{ old('amount', @$data->amount) }}"
                        class="form-control form-control-solid" id="amount" placeholder="Enter Amount"  />
                    @if ($errors->has('amount'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('amount') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5 percentageDiv d-none">
                    <label for="percentage" class="form-label">{{ __('Percentage') }}</label>
                    <input type="text" name="percentage" value="{{ old('percentage', @$data->percentage) }}"
                        class="form-control form-control-solid" id="percentage" placeholder="Enter Percentage"
                        min="0" max="100" step="any" />
                    @if ($errors->has('percentage'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('percentage') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label for="remarks" class="form-label">{{ __('Remark') }}</label>
                    <textarea name="remarks" class="form-control form-control-solid" rows="5" id="remarks kt_docs_ckeditor_classic"
                        placeholder="Enter Remark">{{ old('remarks', @$data->remarks) }}</textarea>
                    @if ($errors->has('remarks'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::Meta options-->
    </div>
</div>
<!--end::Tab pane-->
