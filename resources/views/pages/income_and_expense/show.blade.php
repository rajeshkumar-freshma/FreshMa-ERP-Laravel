<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::details View-->
        <div class="card mb-2" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Income Expense Details</h3>
                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                {{-- <a href="{{ route('admin.income-and-expense.edit', @$incomeExpenseData->id) }}"
                    class="btn btn-sm btn-primary align-self-center" target="_blank">Edit</a> --}}
                <!--end::Action-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show" id="dailyStockDetailsCollapse">
                {{-- <div class="card-body p-9">
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!-- Loop through each field of the income_expense_transactions table -->
                        @foreach ($incomeExpenseData->toArray() as $key => $value)
                            <!-- Check if the field is not null or empty -->
                            @if (!empty($value))
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 fw-semibold text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <div class="col-lg-8">
                                    <!-- Display the value of the field -->
                                    <span class="fw-bold fs-6 text-gray-800">{{ $value }}</span>
                                </div>
                                <!--end::Col-->
                            @endif
                        @endforeach
                    </div>
                    <!--end::Row-->
                </div> --}}
                <div class="card-body p-9">
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Invoice Number</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData->expense_invoice_number)
                            <div class="col-lg-8">
                                <span
                                    class="fw-bold fs-6 text-gray-800">{{ @$incomeExpenseData->expense_invoice_number }}</span>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                        @endif
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Store</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData->store_id)
                            <div class="col-lg-8">
                                <span
                                    class="fw-bold fs-6 text-gray-800">{{ @$incomeExpenseData->store->store_name }}</span>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                        @endif

                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Expense Type</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData->income_expense_type_id)
                            <div class="col-lg-8 fv-row">
                                <span
                                    class="fw-semibold text-gray-800 fs-6">{{ @$incomeExpenseData->income_expense_types->name ?? '' }}</span>
                            </div>
                        @else
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">-</span>
                            </div>
                        @endif

                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Transaction Date
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Transaction Date"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData->transaction_datetime)
                            <div class="col-lg-8 d-flex align-items-center">
                                <span
                                    class="fw-bold fs-6 text-gray-800 me-2">{{ @$incomeExpenseData->transaction_datetime ? \Carbon\Carbon::parse(@$incomeExpenseData?->transaction_datetime)->format('d-m-Y') : '' }}</span>
                            </div>
                        @else
                            <div class="col-lg-8 d-flex align-items-center">
                                <span class="fw-bold fs-6 text-gray-800 me-2">-</span>
                            </div>
                        @endif

                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Sub Total</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData->sub_total)
                            <div class="col-lg-8">
                                <a href="#"
                                    class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ @$incomeExpenseData->sub_total }}</a>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">-</a>
                            </div>
                        @endif

                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Adjustment Amount
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Adjustment Amount"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData->adjustment_amount)
                            <div class="col-lg-8">
                                <span
                                    class="fw-bold fs-6 text-gray-800">{{ @$incomeExpenseData->adjustment_amount }}</span>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                        @endif

                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Total Amount</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        @if (@$incomeExpenseData?->total_amount)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ @$incomeExpenseData?->total_amount }}</span>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                        @endif
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Status</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        @if (@$incomeExpenseData->status == 1)
                            <div class="col-lg-8">
                                <span class="badge badge-success">Active</span>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <span class="badge badge-success">In Active</span>
                            </div>
                        @endif

                        <!--begin::Label-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Payment Status</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">
                                @foreach (config('app.payment_status') as $item)
                                    @if (@$incomeExpenseData?->payment_status == $item['value'])
                                        <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
                                    @endif
                                @endforeach
                            </span>
                        </div>


                        <!--end::Col-->
                    </div>

                </div>
                <!--end::Card body-->
            </div>
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Payment Transactions Details</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show">
                <div class="card-body p-9">
                    @foreach ($incomeExpenseData->incomeExpensePaymentTransaction as $transaction)
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Transaction Type</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">
                                    @foreach (config('app.payment_transactions_type') as $item)
                                        @if ($transaction['transaction_type'] == $item['value'])
                                            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Type</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">
                                    @foreach (config('app.payment_type') as $item)
                                        @if ($transaction['type'] == $item['value'])
                                            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Payment Type</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">
                                    {{ $transaction['payment_type_details']['payment_type'] ?? '-' }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Transaction Date</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 d-flex align-items-center">
                                <span class="fw-bold fs-6 text-gray-800 me-2">
                                    {{ $transaction['transaction_datetime'] ? \Carbon\Carbon::parse($transaction['transaction_datetime'])->format('d-m-Y') : '-' }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Amount</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 d-flex align-items-center">
                                <span class="fw-bold fs-6 text-gray-800 me-2">
                                    {{ $transaction['amount'] ?? '-' }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Status</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">
                                    {{ $transaction['status'] ?? '-' }}
                                </a>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Transaction Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">
                                    {{ $transaction['transaction_number'] ?? '-' }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Status</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="badge badge-{{ $transaction['status'] == 1 ? 'success' : 'danger' }}">
                                    {{ $transaction['status'] == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    @endforeach
                </div>
                <!--end::Card body-->
            </div>

        </div>
        <!--end::details View-->
    </div>
    <!--end::Content container-->
    @include('pages.partials.form_footer', [
        'show_reset' => false,
        'show_save' => false,
        'is_save' => false,
        'back_url' => route('admin.income-and-expense.index'),
    ])
    </div>
</x-default-layout>
