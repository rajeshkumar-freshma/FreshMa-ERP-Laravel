<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Add Cash Paid to Office',
            'menu_1_link' => route('admin.cash-paid-office.index'),
            'menu_2' => 'Add Cash Paid to Office',
        ])
    @endsection
    <!--begin::Basic info-->
    <form id="purchase_order" class="form" method="POST" action="{{ route('admin.cash-paid-office.store') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="card mt-2">
            <!--begin::Card header-->
            @include('pages.partials.form_header', ['header_name' => 'Add Cash Paid'])
            <!--begin::Card header-->

            <!--begin::Content-->
            <div id="purchase_order_return">
                <!--begin::Form-->
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="store_id" class="required form-label required">{{ __('Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach (@$store as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == old('store_id') ? 'selected' : '' }}>
                                                    {{ $value->store_name }}
                                                </option>
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
                        <input type="hidden" id="selected_payer_id" name="selected_payer_id" value="{{ old('payer_id') }}">

                        <div class="col-md-4 store_div">
                            <div class="mb-5">
                                <label for="payer_id" class=" form-label">{{ __('Payer') }} </label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="payer_id" id="payer_id" aria-label="{{ __('Select Payer') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Payer..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true">
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
                        <div class="col-md-4 store_div">
                            <div class="mb-5">
                                <label for="receiver_id"
                                    class="required form-label required">{{ __('Receiver') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="receiver_id" id="receiver_id"
                                            aria-label="{{ __('Select Receiver') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Receiver..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Receiver..') }}</option>
                                            @foreach (@$receivers as $key => $value)
                                                <option data-kt-flag="{{ $value->id }}" value="{{ $value->id }}"
                                                    {{ $value->id == old('receiver_id') ? 'selected' : '' }}>
                                                    {{ $value->first_name . ' - ' . $value->user_code ?? '-' }}
                                                </option>
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
                                <label for="signature" class=" form-label">{{ __('Signature') }}</label>
                                <input class="form-control form-control-sm" name="signature" type="file"
                                    id="signature" value="{{ old('signature') }}">
                                @if ($errors->has('signature'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('signature') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="file" class="form-label">{{ __('Attachments') }}</label>
                                <input class="form-control form-control-sm" name="file" type="file" id="file"
                                    value="{{ old('file') }}">
                                @if ($errors->has('file'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="file" class="form-label">{{ __('Last Updated Date') }}</label>
                                <input class="form-control form-control-sm" id="lastUpdatedDate" value="{{ $lastUpdatedDate }}" type="text" readonly>
                            </div>
                        </div>
                            <!--begin::Label-->
                            <div class="col-md-4">
                                <div class="mb-5">
                                    <label for="amount" class="required form-label required">{{ __('Total Amount') }}</label>
                                    <input class="form-control form-control-sm" name="amount" type="number" id="amount"
                                        value="{{ old('amount') }}" readonly required>
                                </div>
                                @if(session('error'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong> {{ session('error') }}</strong>
                                </span>
                            @endif
                            </div>
                            <div class="col-md-4">
                                <div class="mb-5">
                                    <label for="notes" class=" form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control form-control-sm form-control-solid" name="notes" id="notes" cols="30"
                                        rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col-md-6" id="notUpdatedDates"></div>
                                <div class="col-md-6">
                                <div class="card">
                                        Currency denomination:
                                    <div class="card-body">
                                        <div class="form-row">
                                            <!-- Dynamically generated denomination inputs -->
                                            <table class="table">
                                                @foreach ($denominations as $denomination)
                                                <tr>
                                                    <th>
                                                        <label for="denomination_{{ $denomination->value }}" class="form-label">₹{{ $denomination->value }}</label>
                                                    </th>
                                                    <td>
                                                        <input class="form-control denomination" type="number" id="denomination_{{ $denomination->value }}" data-denomination="{{ $denomination->value }}" name="denominations[{{ $denomination->id }}]" min="0">
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
                                                <td><input class="form-control form-control-sm text-dark" type="text" name="total" id="total" readonly=""></td>
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
