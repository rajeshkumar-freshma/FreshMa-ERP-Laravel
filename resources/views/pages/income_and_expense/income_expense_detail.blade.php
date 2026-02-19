<div class="card mt-2">
    <!--begin::Card header-->
    @include('pages.partials.form_collapse_header', [
        'header_name' => 'Income Expense Details',
        'card_key' => 'expense_details',
    ])
    <!--begin::Card header-->

    <div id="">
        <!--begin::Card body-->
        <div class="border-top px-9 py-4">
            <!--begin::Input group-->
            @if ($is_old_data == 'old_value')
                @foreach ($data['expense_type_id'] as $key => $item)
                    <div class="row mb-3 add_more_expense_data append_expense_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" class="form-control form-control-sm form-control-solid expense_id"
                                name="expense[expense_id][]" data-loop="{{ $key }}"
                                id="expense_id{{ $key }}" value="{{ $data['expense_id'][$key] }}" />
                            <div class="mb-5">
                                <label class="form-label required">{{ __('Income/Expense Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="expense[expense_type_id][]" data-loop="{{ $key }}"
                                            id="expense_type{{ $key }}"
                                            aria-label="{{ __('Select Income/Expense Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Income/Expense Type..') }}"
                                            class="form-select form-select-sm form-select-solid expense_type"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Income/Expense Type..') }}</option>
                                            {{-- @forelse (@$expense_types as $keys => $expense_type)
                                                <option value="{{ $expense_type->id }}"
                                                    {{ $expense_type->id == $item->ie_type_id ? 'selected' : '' }}>
                                                    {{ $expense_type->name }}</option>
                                                <option value="">No Data</option>
                                            @empty
                                                <option value="">No Data</option>
                                            @endforelse --}}
                                            @foreach ($expense_types as $keys => $expense_type)
                                                <option value="{{ $expense_type->id }}"
                                                    {{ $expense_type->id == $data['expense_type_id'][$key] ? 'selected' : '' }}>
                                                    {{ $expense_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expense_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expense_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-5">
                                <label class=" form-label required">{{ __('Income/Expense Amount') }}</label>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid expense_amount"
                                    name="expense[expense_amount][]" data-loop="{{ $key }}"
                                    id="expense_amount{{ $key }}"
                                    value="{{ $data['expense_amount'][$key] }}" required/>
                                @if ($errors->has('transport_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-5">
                                <label class=" form-label">{{ __('Remarks') }}</label><br>
                                <textarea name="expense[remarks][]" data-loop="{{ $key }}"
                                    id="remarks{{ $key }}"class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="remarks">{{ old('remarks') }}</textarea>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-5">
                                <label class=" form-label">&nbsp;</label>
                                <br>
                                <button type="button" class="btn btn-sm btn-primary add_expense_data"><i
                                        class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-danger remove_expense_data"
                                    data-loop="{{ $key }}"><i class="fa fa-close"></i></button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif ($is_old_data == 'edit_value' && count($data) > 0)
                @foreach ($data as $key => $item)
                    <div class="row mb-3 add_more_expense_data append_expense_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" class="form-control form-control-sm form-control-solid expense_id"
                                name="expense[expense_id][]" data-loop="{{ $key }}"
                                id="expense_id{{ $key }}" value="{{ $item->id }}" />
                            <div class="mb-5">
                                <label class="form-label required">{{ __('Income/Expense Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="expense[expense_type_id][]" data-loop="{{ $key }}"
                                            id="expense_type{{ $key }}"
                                            aria-label="{{ __('Select Income/Expense Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Income/Expense Type..') }}"
                                            class="form-select form-select-sm form-select-solid expense_type"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Income/Expense Type..') }}</option>
                                            @forelse (@$expense_types as $keys => $expense_type)
                                                <option value="{{ $expense_type->id }}"
                                                    {{ $expense_type->id == $item->ie_type_id ? 'selected' : '' }}>
                                                    {{ $expense_type->name }}</option>
                                            @empty
                                                <option value="">No Data</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expense_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expense_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-5">
                                <label class=" form-label required">{{ __('Income/Expense Amount') }}</label>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid expense_amount"
                                    name="expense[expense_amount][]" data-loop="{{ $key }}"
                                    id="expense_amount{{ $key }}" value="{{ $item->amount }}" required/>
                                @if ($errors->has('transport_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expense_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-5">
                                <label class=" form-label">{{ __('Remarks') }}</label><br>
                                <textarea name="expense[remarks][]" data-loop="{{ $key }}"
                                    id="remarks{{ $key }}"class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="remarks">{{ old('remarks', $item->remarks) }}</textarea>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-5">
                                <label class=" form-label">&nbsp;</label>
                                <br>
                                <button type="button" class="btn btn-sm btn-primary add_expense_data"><i
                                        class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-danger remove_expense_data"
                                    data-loop="{{ $key }}"><i class="fa fa-close"></i></button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row mb-3 add_more_expense_data append_expense_data0">
                    <!--begin::Label-->
                    <div class="col-md-4">
                        <input type="hidden" class="form-control form-control-sm form-control-solid expense_id"
                            name="expense[expense_id][]" data-loop="0" id="expense_id0" />
                        <div class="mb-5">
                            <label class="form-label required">{{ __('Income/Expense Type') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="expense[expense_type_id][]" data-loop="0" id="expense_type0"
                                        aria-label="{{ __('Select Income/Expense Type') }}" data-control="select2"
                                        data-placeholder="{{ __('Select Income/Expense Type..') }}"
                                        class="form-select form-select-sm form-select-solid expense_type"
                                        data-allow-clear="true" required>
                                        <option value="">{{ __('Select Income/Expense Type..') }}</option>
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('expense_type_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('expense_type_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-5">
                            <label class=" form-label required">{{ __('Income/Expense Amount') }}</label>
                            <input type="text"
                                class="form-control form-control-sm form-control-solid expense_amount"
                                name="expense[expense_amount][]" data-loop="0" id="expense_amount0" required/>
                            @if ($errors->has('transport_name'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('expense_amount') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-5">
                            <label class=" form-label">{{ __('Remarks') }}</label><br>
                            <textarea name="expense[remarks][]" class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                id="Remarks" placeholder="remarks">{{ old('remarks') }}</textarea>

                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-5">
                            <label class=" form-label">&nbsp;</label>
                            <br>
                            <button type="button" class="btn btn-sm btn-primary add_expense_data"><i
                                    class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            @endif
            <span class="append_expense_details"></span>
            <div class="row mb-3">
                <div class="col-md-6">
                </div>
                <div class="col-md-6 bg-light rounded">
                    @if (Request::is('*/income-and-expense/*'))
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Income/Expense Amount') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row mt-4">
                                    <h3 class="total_expense_amount_display">
                                        {{ old('total_expense_amount_display', isset($income_and_expense_data->total_amount) ? @$income_and_expense_data->total_amount : 0) }}
                                    </h3>
                                    <input type="hidden" name="total_expense_amount_display_val"
                                        class="form-control form-control-sm" id="total_expense_amount_display_val"
                                        value="{{ old('total_expense_amount_display_val', isset($income_and_expense_data->total_amount) ? @$income_and_expense_data->total_amount : 0) }}" required>
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                    @endif
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
</div>
