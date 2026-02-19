<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Loan Repayment Details</h3>
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
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Loan Paymment Date</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ $loan_repayment->payment_date ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Pay Amount </p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan_repayment->pay_amount != null ? @$loan_repayment->pay_amount : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Due Amount</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$loan_repayment->due_amount != null ? @$loan_repayment->due_amount : '-' }}
                            </p>
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
