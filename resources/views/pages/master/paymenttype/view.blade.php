<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Payment Details</h3>
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
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Payment Type</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$paymentType->payment_type ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Slug</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$paymentType->slug ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Payment Category</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @php
                                $paymentCategory = config('app.payment_category');
                                foreach ($paymentCategory as $key => $value) {
                                    if ($value['value'] == @$paymentType->payment_category) {
                                        $categoryName = $value['name'];
                                        break;
                                    }
                                }
                            @endphp
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ $categoryName ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Machine Number</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$paymentType->machine_number ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Store</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @php
                                foreach (@$stores as $key => $value) {
                                    if ($value->id == @$paymentType->store_id) {
                                        $storeName = $value->store_name;
                                        break;
                                    }
                                }
                            @endphp
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$storeName ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Status</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$paymentType->status == 1 ? 'Active' : 'Deactive' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>
