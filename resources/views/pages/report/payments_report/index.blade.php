<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Payment Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Payment Report',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="{{ route('admin.payments_report') }}" method="GET"enctype="multipart/form-data">
                @csrf
                @method('get')
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="from_date" value="{{ old('from_date', @$from_date) }}" />
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
                                <input id="to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="to_date" value="{{ old('to_date', @$to_date) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="submit"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.payments_report') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body pt-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-5">
                        @include('pages.partials.form_header', ['header_name' => 'Purchase'])
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"><strong>{{ __('Transaction Type') }}</strong></th>
                                        <th><strong>{{ __('Credit') }}</strong></th>
                                        <th scope="col"><strong>{{ __('Debit') }}</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseTransactions as $paymentType)
                                        <tr>
                                            <td>{{ $paymentType->payment_type }}</td>
                                            <td>{{ $paymentType->paymentTransactions->sum('credit_sum') }}</td>
                                            <td>{{ $paymentType->paymentTransactions->sum('debit_sum') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-5">
                        @include('pages.partials.form_header', ['header_name' => 'Sales'])
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><strong>{{ __('Transaction Type') }}</strong></th>
                                        <th><strong>{{ __('Credit') }}</strong></th>
                                        <th><strong>{{ __('Debit') }}</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesTransactions as $paymentType)
                                        <tr>
                                            <td>{{ $paymentType->payment_type }}</td>
                                            <td>{{ $paymentType->paymentTransactions->sum('credit_sum') }}</td>
                                            <td>{{ $paymentType->paymentTransactions->sum('debit_sum') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body pt-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-5">
                        @include('pages.partials.form_header', ['header_name' => 'Income/Expense'])
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><strong>{{ __('Transaction Type') }}</strong></th>
                                        <th><strong>{{ __('Income') }}</strong></th>
                                        <th><strong>{{ __('Expense') }}</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incomeExpense as $paymentType)
                                        <tr>
                                            <td>{{ $paymentType->payment_type }}</td>
                                            <td>{{ $paymentType->paymentTransactions->sum('income_sum') }}</td>
                                            <td>{{ $paymentType->paymentTransactions->sum('expense_sum') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="card-body">
            @include('pages.payment.transactions_report._table')
        </div> --}}
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    @section('scripts')
        @include('pages.partials.date_picker_script')
    @endsection
</x-default-layout>
