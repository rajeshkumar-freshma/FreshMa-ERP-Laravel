<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Cash Register',
            'menu_1_link' => route('admin.cash-register.index'),
            'menu_2' => 'Add Cash Register',
        ])
    @endsection
    <!--begin::Basic info-->
    <form id="purchase_order_return_form" class="form" method="POST" action="{{ route('admin.cash-register.store') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Add Cash Register'])

            @foreach (@$store as $key => $value)
                @if (@$store_id == $value->id)
                    <b class="m-3">
                        {{ $value->store_name }} - Status:
                        @foreach (config('app.is_opened') as $item)
                            @if (@$cash_register->is_opened == $item['value'])
                                <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
                            @endif
                        @endforeach
                    </b>
                @endif
            @endforeach

            <!--begin::Card body for additional data-->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card mb-3 bg-secondary text-white">
                                <div class="card-body">
                                    <p class="mb-0">Opening Cash: {{ @$cash_register->amount ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-3 bg-info text-white">
                                <div class="card-body">
                                    <p class="mb-0">Current Cash: {{ @$total_amount }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-3 bg-primary text-white">
                                <div class="card-body">
                                    <p class="mb-0">Cash Paid To Office: {{ @$cash_paid_to_office }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-3 bg-success text-white">
                                <div class="card-body">
                                    <p class="mb-0">Today Credit Sales: {{ @$today_received_credit_amount }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-3 bg-warning text-white">
                                <div class="card-body">
                                    <p class="mb-0">Expenses Amount: {{ @$total_expense_amount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-3 bg-danger text-white">
                                <div class="card-body">
                                    <p class="mb-0">Sale Amount: {{ @$total_sale_amount }}</p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <!--end::Card body for additional data-->

            <!--begin::Content-->
            <div id="cash-register">
                <!--begin::Form-->
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <input type="text" name="store_id" id="store_id" value="{{ @$store_id }}" hidden>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="is_opened" class="required form-label">{{ __('Is Opened') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="is_opened" id="is_opened" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Is Opened..') }}</option>
                                            <option value=1 {{ old('is_opened') == 1 ? 'selected' : '' }}>
                                                {{ __('Opened') }}
                                            </option>
                                            <option value=2 {{ old('is_opened') == 2 ? 'selected' : '' }}>
                                                {{ __('Closed') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('is_opened'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('is_opened') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="open_close_time"
                                    class="required form-label">{{ __('Open Or Close Time ') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-calendar"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input id="open_close_time" type="text" value="{{ old('open_close_time') }}"
                                            class="form-control form-control-solid fsh_flat_datepicker p-2"
                                            name="open_close_time" placeholder="Open Close Time" />
                                    </div>
                                </div>
                                @if ($errors->has('open_close_time'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('open_close_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="amount" class="required form-label">{{ __('Opening Cash') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-money"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input id="amount" type="text" class="form-control form-control-solid p-2"
                                            value="{{ old('amount') }}" name="amount" placeholder="Amount" required />
                                    </div>
                                </div>
                                @if ($errors->has('amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="add_dedect_amount"
                                    class="required form-label">{{ __('Add Dedect Amount') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-money"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input id="add_dedect_amount" type="text"
                                            class="form-control form-control-solid p-2" name="add_dedect_amount"
                                            value="{{ old('add_dedect_amount') }}" placeholder="Add Dedect Amount"
                                            required />
                                    </div>
                                </div>
                                @if ($errors->has('add_dedect_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('add_dedect_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="total_amount"
                                    class="required form-label">{{ __('Total Amount') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-money"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input id="total_amount" type="text"
                                            class="form-control form-control-solid p-2" name="total_amount"
                                            value="{{ old('total_amount') }}" placeholder="Total Amount" required />
                                    </div>
                                </div>
                                @if ($errors->has('total_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('total_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transaction_type"
                                    class="required form-label">{{ __('Transaction Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transaction_type" id="transaction_type"
                                            aria-label="{{ __('Select Transaction Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Transaction Type..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Transaction Type..') }}
                                            </option>
                                            @foreach (config('app.cash_register_transaction_type') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('transaction_type') ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="verified_by" class="required form-label">{{ __('Is Verified') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="verified_by" id="verified_by"
                                            aria-label="{{ __('Select Is Verified') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Is Verified..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Is Verified..') }}</option>
                                            @foreach (@$admin as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}"
                                                    value="{{ $value->id }}"
                                                    {{ $value->id == old('verified_by') ? 'selected' : '' }}>
                                                    {{ $value->first_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('verified_by'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('verified_by') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="status" class="required form-label">{{ __('Status') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
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
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Content-->
        </div>
        <!--begin::Actions-->
        @include('pages.partials.form_footer', [
            'is_save' => true,
            'back_url' => route('admin.cash-register.index'),
        ])
        <!--end::Actions-->
    </form>
    @section('scripts')
        @include('pages.partials.date_picker')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Get the selected store_id value
                var selectedStoreId = $("#store_id").val();

                // Log the selected store_id value to the console
                console.log(selectedStoreId);
                console.log("selectedStoreId");

                // You can now use the selectedStoreId value in your script as needed
                // For example, you might want to perform some action based on this value
            });
        </script>
    @endsection
</x-default-layout>
