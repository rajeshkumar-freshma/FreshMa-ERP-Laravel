<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Cash Register Details</h3>
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
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_registers_data->store->store_name != null ? @$cash_registers_data->store->store_name : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Is Open</p>
                        </div>
                        <div class="col-6 col-md-4">
                            @foreach (config('app.is_opened') as $item)
                                @if ($item['value'] == @$cash_registers_data->is_opened)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ $item['name'] }}</p>
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
                                {{ @$cash_registers_data->amount }}</p>
                        </div>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Add Dedect Amount</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_registers_data->add_dedect_amount }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Total Amount</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_registers_data->total_amount }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Open Close Time</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_registers_data->open_close_time }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Transaction Type</p>
                        </div>
                        <div class="col-6 col-md-4">
                            @foreach (config('app.cash_register_transaction_type') as $item)
                                @if ($item['value'] == @$cash_registers_data->transaction_type)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ $item['name'] }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Verified By</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$cash_registers_data->verified->first_name }}</p>
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
                                {{ @$cash_registers_data->status == 1 ? 'Active' : 'inactive' }}
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
