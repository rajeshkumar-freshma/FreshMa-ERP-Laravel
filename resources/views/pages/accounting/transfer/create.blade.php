<x-default-layout>
    @section('_toolbar')
        @php
            $data['title'] = 'Transfer';
            $data['menu_1'] = 'Transfer';
            $data['menu_1_link'] = route('admin.transfer.index');
            $data['menu_2'] = 'Create';
        @endphp
        @include(config('settings.KT_THEME_LAYOUT_DIR') . '/partials/sidebar-layout/_toolbar', [
            'data' => $data,
        ])
    @endsection

    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Transfer'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="tranfer" class="collapse show">
            <!--begin::Form-->
            <form id="tranfer_form" class="form" method="POST" action="{{ route('admin.transfer.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="from_account_id" onchange="getBankBalance()"
                                    class="form-label required">{{ __('From Account') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="from_account_id" id="from_account_id"
                                            aria-label="{{ __('Select From Account') }}" data-control="select2"
                                            data-placeholder="{{ __('Select From Account..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select From Account..') }}</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    {{ old('from_account_id') ? 'selected' : '' }}>
                                                    {{ $value->account_holder_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('from_account_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_account_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="to_account_id" class="form-label required">{{ __('To Account') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="to_account_id" id="to_account_id"
                                            aria-label="{{ __('Select To Account') }}" data-control="select2"
                                            data-placeholder="{{ __('Select To Account..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select To Account..') }}</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    {{ old('to_account_id') ? 'selected' : '' }}>
                                                    {{ $value->account_holder_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('to_account_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_account_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="available_balance"
                                    class="required form-label">{{ __('Availabe Bank Balance') }}</label>
                                <input type="decimal" name="available_balance" value="{{ old('available_balance') }}"
                                    class="form-control form-control-sm form-control-solid" id="available_balance"
                                    placeholder="Bank Balance" readonly required />
                                @if ($errors->has('available_balance'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('available_balance') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="transfer_amount" onchange="transferAmount()"
                                    class="required form-label">{{ __('Transfer Amount') }}</label>
                                <input type="decimal" name="transfer_amount" value="{{ old('transfer_amount') }}"
                                    class="form-control form-control-sm form-control-solid" id="transfer_amount"
                                    placeholder="Enter Transfer Amount" required />
                                @if ($errors->has('transfer_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="transaction_date"
                                    class="form-label required">{{ __('Transfer Date') }}</label>
                                <div class="input-group">
                                    <input id="transaction_date" type="text"
                                        class="form-control form-control-solid fsh_flat_datepicker"
                                        name="transaction_date" placeholder="Enter Joined Date"
                                        value="{{ old('transaction_date') }}" required/>
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('transaction_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="transfer_reason" class=" required form-label">{{ __('Transfer Reasons') }}</label>
                                <input type="text" name="transfer_reason" value="{{ old('transfer_reason') }}"
                                    class="form-control form-control-sm form-control-solid" id="transfer_reason"
                                    placeholder="Enter Tranfer Reason" required/>
                                @if ($errors->has('transfer_reason'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transfer_reason') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control form-control-sm form-control-solid" id="notes"
                                    placeholder="Enter Notes">{{ old('notes') }}</textarea>
                                @if ($errors->has('notes'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('notes') }}</strong>
                                    </span>
                                @endif
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
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
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
                    </div>
                </div>
                <input type="hidden" name="submission_type" id="submission_type" value="">

                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.transfer.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.accounting.transfer.script')
    @endsection
</x-default-layout>
