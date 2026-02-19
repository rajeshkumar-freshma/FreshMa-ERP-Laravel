<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Cash Paid to Office Details</h3>
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
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Store Name</p>
                        </div>
                        <div class="col-6 col-md-4">
                            @foreach (@$store as $item)
                                @if (@$item->id == $cash_paid_office->store_id)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ @$item->store_name != null ? @$item->store_name : '-' }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Payer</p>
                        </div>
                        <div class="col-6 col-md-4">
                            @foreach (@$admin as $item)
                                @if (@$item->id == $cash_paid_office->payer_id)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ @$item->first_name != null ? @$item->first_name : '-' }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Receiver</p>
                        </div>
                        <div class="col-6 col-md-4">
                            @foreach (@$admin as $item)
                                @if (@$item->id == @$cash_paid_office->receiver_id)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ @$item->first_name != null ? @$item->first_name : '-' }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Amount</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_paid_office->amount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Staus</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_paid_office->status == 1 ? 'Active' : 'inactive' }}
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
