<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Transactions',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Transactions',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="#">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="from_date" value="{{ old('from_date') }}"/>
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_date" class=" form-label">{{ __('To Date') }}</label>
                            <div class="input-group">
                                <input id="to_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="to_date" value="{{ old('to_date') }}"/>
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transaction_type" class="form-label">{{ __('Transaction Type') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="transaction_type" id="transaction_type" aria-label="{{ __('Select Transaction Type') }}" data-control="select2" data-placeholder="{{ __('Select Transaction Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" >
                                        <option value="">{{ __('Select Transaction Type..') }}</option>
                                        @foreach (config('app.payment_transactions_type') as $key => $value)
                                                <option value="{{ $value['value'] }}">
                                                    {{  $value['name'] }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="type" id="type" aria-label="{{ __('Select Type') }}" data-control="select2" data-placeholder="{{ __('Select Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" >
                                        <option value="">{{ __('Select Type..') }}</option>
                                        @foreach (config('app.payment_type') as $key => $value)
                                                <option value="{{ $value['value'] }}">
                                                    {{  $value['name'] }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="payment_type_id" class="form-label">{{ __('Payment Type') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="payment_type_id" id="payment_type_id" aria-label="{{ __('Select Payment Type') }}" data-control="select2" data-placeholder="{{ __('Select Payment Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" >
                                        <option value="">{{ __('Select Payment Type..') }}</option>
                                        @foreach (@$paymentTypes as $key => $value)
                                                <option value="{{ $value->id }}">
                                                    {{  $value->payment_type }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="button" class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.payment-transaction-report.index') }}"><button type="button" class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @include('pages.payment.transactions_report._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
