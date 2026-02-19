<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Income Expense',
            'menu_1_link' => route('admin.income-and-expense.index'),
            'menu_2' => 'Edit Income Expense',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Income/Expense Update'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="income_expense" class="collapse show">
            <!--begin::Form-->
            <form id="income_expense_form" class="form" method="POST"
                action="{{ route('admin.income-and-expense.update', @$income_and_expense_data->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Income/Expense</h2>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="date" class="required form-label">{{ __('Date') }}</label>
                                <div class="input-group">
                                    <input id="date" type="button"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker "
                                        name="date"
                                        value="{{ old('date', @$income_and_expense_data->transaction_datetime) }}"
                                        required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="related_to" class="required form-label">{{ __('Related To') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="related_to" id="related_to"
                                            aria-label="{{ __('Select Related To') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Related..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Related To..') }}</option>
                                            @foreach (config('app.related_to') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('related_to', @$income_and_expense_data->related_to) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('related_to'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('related_to') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4" id="store_model" style="display: none;">
                            <div class="mb-5">
                                <label for="store_id" class=" form-label">{{ __('Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach (@$stores as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == old('store_id', @$income_and_expense_data->store_id) ? 'selected' : '' }}>
                                                    {{ $value->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('store_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4" id="warehouse_model" style="display: none;">
                            <div class="mb-5">
                                <label for="warehouse_id" class=" form-label">{{ __('Warehouse') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="warehouse_id" id="warehouse_id"
                                            aria-label="{{ __('Select Warehouse') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Warehouse..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            @foreach (@$warehouse as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == old('warehouse_id', @$income_and_expense_data->warehouse_id) ? 'selected' : '' }}>
                                                    {{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('warehouse_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('warehouse_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="income_expense_type_id"
                                    class="required form-label">{{ __('Income/Expense Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="income_expense_type_id" id="income_expense_type_id"
                                            aria-label="{{ __('Select Income/Expense Type') }}"
                                            data-control="select2"
                                            data-placeholder="{{ __('Select Income/Expense Type..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Income/Expense Type..') }}</option>
                                            @foreach (config('app.incomeexpense') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('income_expense_type_id', @$income_and_expense_data->income_expense_type_id) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('income_expense_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('income_expense_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                @php
                                    $income_invoice_number = commoncomponent()->invoice_no('income_expense', 'ICM-');
                                    $expense_invoice_number = commoncomponent()->invoice_no('income_expense');
                                @endphp
                                <label for="income_expense_invoice_number"
                                    class="required form-label">{{ __('Invoice Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    id="income_expense_invoice_number" name="income_expense_invoice_number"
                                    value="{{ old('income_expense_invoice_number', @$income_and_expense_data->expense_invoice_number) }}"
                                    placeholder="Invoice Number-" required />
                                @if ($errors->has('income_expense_invoice_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('income_expense_invoice_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="expense_documents"
                                    class="required form-label">{{ __('Income/Expense Attachment') }}</label>
                                <input id="expense_documents" type="file"
                                    class="form-control form-control-sm form-control-solid" name="expense_documents"
                                    placeholder="Document/Attachments" value="{{ old('expense_documents') }}" />
                                @if ($errors->has('expense_documents'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expense_documents') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                {{-- adding expense details --}}
                @if (old('expense.expense_type_id'))
                    @include('pages.income_and_expense.income_expense_detail', [
                        'data' => old('expense'),
                        'is_old_data' => 'old_value',
                    ])
                    @php
                        $expense_count = count(old('expense'));
                    @endphp
                @elseif(count(@$income_and_expense_data->income_expense_details) > 0)
                    @include('pages.income_and_expense.income_expense_detail', [
                        'data' => $income_and_expense_data->income_expense_details,
                        'is_old_data' => 'edit_value',
                    ])
                    @php
                        $expense_count = count(@$income_and_expense_data->income_expense_details);
                    @endphp
                @else
                    @include('pages.income_and_expense.income_expense_detail', [
                        'data' => [],
                        'is_old_data' => 'default',
                    ])
                    @php
                        $expense_count = 0;
                    @endphp
                @endif

                {{-- adding payment details --}}
                @if (old('payment_details.payment_type_id'))
                    @include('pages.partials.payment_detail', [
                        'payment_details' => old('payment_details'),
                        'is_old_data' => 'old_value',
                    ])
                    @php
                        $payment_count = count(old('payment_details'));
                    @endphp
                @elseif(count(@$income_and_expense_data->incomeExpensePaymentTransaction) > 0)
                    @include('pages.partials.payment_detail', [
                        'payment_details' => $income_and_expense_data->incomeExpensePaymentTransaction,
                        'is_old_data' => 'edit_value',
                    ])
                    @php
                        $payment_count = count(@$income_and_expense_data->incomeExpensePaymentTransaction);
                    @endphp
                @else
                    @include('pages.partials.payment_detail', [
                        'data' => [],
                        'is_old_data' => 'default',
                    ])
                    @php
                        $payment_count = 0;
                    @endphp
                @endif


                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.income-and-expense.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->

        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
        @include('pages.partials.date_picker')
        @include('pages.income_and_expense.expense_add_more_new')
        @include('pages.partials.payment_detail_addmore')
    @endsection
</x-default-layout>
