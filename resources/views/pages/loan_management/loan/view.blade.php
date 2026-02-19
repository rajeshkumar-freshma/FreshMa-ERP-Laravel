<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Loan Details</h3>
            <div class="card-tools px-sm-4 px-md-1 px-lg-2 px-xl-3" title="Minimize/Maximize">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row text-center px-sm-4 px-md-1 px-lg-2 px-xl-3">
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Borrower Name</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @foreach (@$employee as $item)
                                @if (@$item->id == $loan->employee_id)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ $item->first_name ?? '-' }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Applied On</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan->applied_on != null ? @$loan->applied_on : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Principal Amount</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan->principal_amount != null ? @$loan->principal_amount : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Applied Amount</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan->applied_amount != null ? @$loan->applied_amount : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Repayment Amount</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan->repayment_amount != null ? @$loan->repayment_amount : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Interest Rate</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan->interest_rate != null ? @$loan->interest_rate : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Interets Frequency</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @foreach (config('app.interest_frequency') as $item)
                                @if (@$item['value'] == $loan->interest_frequency)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ $item['name'] ?? '-' }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Loan Tenure</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan->loan_tenure != null ? @$loan->loan_tenure : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Staus</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @foreach (config('app.loan_status') as $item)
                                @if (@$item['value'] == $loan->loan_status)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ $item['name'] ?? '-' }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
