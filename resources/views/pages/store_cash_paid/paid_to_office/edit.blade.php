<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Cash Paid to Office',
            'menu_1_link' => route('admin.cash-paid-office.index'),
            'menu_2' => 'Edit Cash Paid to Office',
        ])
    @endsection
    <!--begin::Basic info-->
    <form id="purchase_order_return_form" class="form" method="POST"
        action="{{ route('admin.cash-paid-office.update', @$cashPaidTooffice->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Update Cash Paid'])
            <!--begin::Card header-->

            <!--begin::Content-->
            <div id="purchase_order_return">
                <!--begin::Form-->
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4 store_div">
                            <div class="mb-5">
                                <label for="store_id" class="form-label required">{{ __('Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            aria-required="true" readonly>
                                            <!-- Loop through the Store options and set the selected attribute for the appropriate one -->
                                            @foreach (@$store as $key => $value)
                                                @if ($value->id == @$cashPaidTooffice->store_id)
                                                    <option value="{{ $value->id }}"
                                                        {{ $value->id == @$cashPaidTooffice->store_id ? 'selected' : '' }}>
                                                        {{ $value->store_name }}
                                                    </option>
                                                @endif
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

                        <div class="col-md-4 store_div">
                            <div class="mb-5">
                                <label for="payer_id" class="form-label required">{{ __('Payer') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="payer_id" id="payer_id" aria-label="{{ __('Select Payer') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Payer..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            aria-required="true" readonly>
                                            <!-- Options will be dynamically added here -->
                                        </select>

                                    </div>
                                </div>
                                @if ($errors->has('payer_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payer_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <input type="hidden" id="selected_payer_id" name="selected_payer_id" value="{{ old('payer_id', $cashPaidTooffice->payer_id ?? '') }}">
                        <div class="col-md-4 store_div">
                            <div class="mb-5">
                                <label for="receiver_id" class="form-label required">{{ __('Receiver') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="receiver_id" id="receiver_id"
                                            aria-label="{{ __('Select Receiver') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Receiver..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            aria-required="true" readonly>
                                            <option value="">{{ __('Select Receiver..') }}</option>
                                            @foreach (@$receiver as $key => $value)
                                                @if ($value->id == @$cashPaidTooffice->receiver_id)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == @$cashPaidTooffice->receiver_id ? 'selected' : '' }}>
                                                    {{ $value->first_name . ' - ' . $value->user_code }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('receiver_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('receiver_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="signature" class="form-label"
                                    aria-required="true">{{ __('Signature') }}</label>
                                <input class="form-control form-control-sm" name="signature" type="file"
                                    id="signature" value="{{ @$cashPaidTooffice->signature }}">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="file" class="form-label">{{ __('Attachments') }}</label>
                                <input class="form-control form-control-sm" name="file" type="file" id="file"
                                    value="{{ @$cashPaidTooffice->file }}">
                                </select>
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
                                                    {{ $value['value'] == @$cashPaidTooffice->status ? 'selected' : '' }}>
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <!-- Display last updated date -->
                                <label for="file" class="form-label">{{ __('Last Updated Date') }}</label>
                                <input class="form-control form-control-sm" value="{{ $lastUpdatedDate }}" id="lastUpdatedDate" type="text" readonly>
                            </div>
                        </div>
                        <input type="hidden" id="cashPaidToOfficeId" value="{{ $cashPaidTooffice->id }}">
                        <!-- Display not updated dates fetched via AJAX -->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="amount" class="form-label required">{{ __('Total Amount') }}</label>
                                <input class="form-control form-control-sm" name="amount" type="text" id="amount"
                                    value="{{ @$cashPaidTooffice->amount }}" aria-required="true">
                                </select>
                            </div>
                            @if(session('error'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong> {{ session('error') }}</strong>
                            </span>
                        @endif
                        </div>
                            <!--begin::Label-->
                            <div class="col-md-4">
                                <div class="mb-5">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control form-control-sm form-control-solid" name="notes" id="notes" cols="30" rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col-md-6" id="notUpdatedDates">
                                    @forelse(@$cashPaidTooffice->denomination_date_amounts as $denomination_date_amount)
                                        <div class="input-group">
                                            <input type="date" name="dates[]"class="date-input form-control" value="{{ $denomination_date_amount->dates }}" placeholder="Date" readonly><br>
                                            <input type="number" name="amounts[]"class="amount-input form-control" value="{{ $denomination_date_amount->amount }}" placeholder="Amount">
                                        </div><br>
                                        @empty
                                        @endforelse
                                </div>
                                <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Currency denomination:
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <!-- Dynamically generated denomination inputs -->
                                            <table class="table">

                                                @foreach (@$cashPaidTooffice->denominations as $cash_paid_to_office_denomination)
                                                <tr>
                                                    <th>
                                                        <label for="denomination_{{ $cash_paid_to_office_denomination->denomination_id }}"
                                                                    class="form-label">₹{{ $cash_paid_to_office_denomination->denomination_types->value }}</label>
                                                    </th>
                                                    <td>
                                                        <input class="form-control denomination" type="number"
                                                        id="denomination_{{ $cash_paid_to_office_denomination->denomination_id }}"
                                                        data-denomination="{{ $cash_paid_to_office_denomination->denomination_types->value }}"
                                                        name="denominations[{{ $cash_paid_to_office_denomination->denomination_id }}]" min="0"
                                                        value="{{ $cash_paid_to_office_denomination->denomination_value }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <table class="table">
                                            <tr>
                                                <td>Currency Total:&nbsp;</td>
                                                <td><input class="form-control form-control-sm text-dark" type="text" name="total" id="total" value="" readonly=""></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                            <!--begin::Label-->
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
            'back_url' => route('admin.cash-paid-office.index'),
        ])
        <!--end::Actions-->
    </form>
    @include('pages.store_cash_paid.paid_to_office.script')
</x-default-layout>
