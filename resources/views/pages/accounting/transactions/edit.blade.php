<x-default-layout>
    @section('_toolbar')
        @php
            $data['title'] = 'Transaction';
            $data['menu_1'] = 'Transaction';
            $data['menu_1_link'] = route('admin.transaction.index');
            $data['menu_2'] = 'Create';
        @endphp
        @include(config('settings.KT_THEME_LAYOUT_DIR') . '/partials/sidebar-layout/_toolbar', [
            'data' => $data,
        ])
    @endsection

    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Transaction'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="transaction" class="collapse show">
            <!--begin::Form-->
            <form id="transaction_form" class="form" method="POST" action="{{ route('admin.transaction.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="transaction_account" onchange="getBankBalance()"
                                    class="form-label required">{{ __('Transaction Account') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transaction_account" id="transaction_account"
                                            aria-label="{{ __('Select Transaction Account') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Transaction Account..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Transaction Account..') }}</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    {{-- {{ $value->id == $transactions->transaction_account ? 'selected' : '' }} --}}
                                                    @selected(old('transaction_account',$transactions->transaction_account) ==  $value->id )
                                                    >
                                                    {{ $value->account_holder_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('transaction_account'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_account') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="transaction_type"
                                    class="form-label required">{{ __('Transaction Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transaction_type" id="transaction_type"
                                            aria-label="{{ __('Select Transaction Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Transaction Type..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Transaction Type..') }}</option>
                                            @foreach (config('app.transaction_type') as $key => $value)
                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                            {{-- {{ $value['value'] == $transactions->transaction_type ? 'selected' : '' }} --}}
                                            @selected(old('transaction_type',$transactions->transaction_type) ==  $value['value']  )
                                            >{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('transaction_type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="available_balance"
                                    class="required form-label">{{ __('Availabe Bank Balance') }}</label>
                                <input type="decimal" name="available_balance"
                                    value="{{ old('available_balance', $transactions->available_balance) }}"
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
                                <label for="transaction_amount" onchange="transactionAmount()"
                                    class="required form-label">{{ __('Transaction Amount') }}</label>
                                <input type="decimal" name="transaction_amount"
                                    value="{{ old('transaction_amount', $transactions->transaction_amount) }}"
                                    class="form-control form-control-sm form-control-solid" id="transaction_amount"
                                    placeholder="Enter Transaction Amount" required />
                                @if ($errors->has('transaction_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="transaction_date" class="form-label required">{{ __('Transaction Date') }}</label>
                                <input id="transaction_date" type="text"
                                    class="form-control form-control-solid fsh_flat_datepicker" name="transaction_date"
                                    placeholder="Enter Transaction Date"
                                    value="{{ old('transaction_date', $transactions->transaction_date) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control form-control-sm form-control-solid" id="notes"
                                    placeholder="Enter Notes">{{ old('notes', $transactions->notes) }}</textarea>
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
                                            <option value="{{ $value['value'] }}" {{ $value['value'] == old('status', $transactions->status) ? 'selected' : '' }}>
                                                {{ $value['name'] }}
                                            </option>
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
                    'back_url' => route('admin.transaction.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.accounting.transactions.script')
    @endsection
</x-default-layout>
