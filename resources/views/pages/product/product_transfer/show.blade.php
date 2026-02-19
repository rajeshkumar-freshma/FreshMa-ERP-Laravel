<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.product.product_transfer.transfernav')
        </div>
        <!--end::Navbar-->
        <!--begin::details View-->
        <div class="card mb-2" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Product Transfer Details</h3>

                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                <a href="{{ route('admin.product-transfer.edit', $product_transfer->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit</a>
                <!--end::Action-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show" id="purchaseDetailsCollapse">
                <div class="card-body p-9">

                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Product Transfer Number</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $product_transfer->transfer_order_number }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Transfer From</label>
                        @if ($product_transfer->transfer_from == 1)
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Warehouse</span>
                        </div>
                        @elseif ($product_transfer->transfer_from == 2)
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Store</span>
                        </div>
                        @else
                        -
                        @endif

                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Transfer To</label>
                        @if ($product_transfer->transfer_to == 1)
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Warehouse</span>
                        </div>
                        @elseif ($product_transfer->transfer_to == 2)
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Store</span>
                        </div>
                        @else
                        -
                        @endif
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">From Warehouse</label>
                        @if (@$product_transfer->from_warehouse_id)
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$product_transfer->from_warehouse->name }}</span>
                        </div>
                        @elseif (@$product_transfer->from_store_id)
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$product_transfer->from_store->store_name }}</span>
                        </div>
                        @else
                        <span class="fw-semibold text-gray-800 fs-6">-</span>
                        @endif

                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">To Store</label>
                        @if (@$product_transfer->to_store_id)
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $product_transfer->to_store->store_name }}</span>
                        </div>
                        @elseif (@$product_transfer->to_warehouse_id)
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$product_transfer->to_warehouse->name }}</span>
                        </div>
                        @else
                        <span class="fw-semibold text-gray-800 fs-6">-</span>
                        @endif

                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Transfer Date</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $product_transfer->transfer_created_date }}</span>
                        </div>
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Receive Date</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $product_transfer->transfer_received_date }}</span>
                        </div> <!--end::Col-->
                    </div>
                </div>
            </div>
            <!--end::details View-->
        </div>
        <!--end::Content container-->
        @include('pages.partials.form_footer', ['show_reset' => false, 'show_save' => false, 'is_save' => false, 'back_url' => route('admin.purchase-order.index')])
    </div>
    </div>
</x-default-layout>
