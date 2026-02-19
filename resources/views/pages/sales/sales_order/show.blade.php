<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.sales.sales_order.sales_nav')
        </div>
        <!--end::Navbar-->
        <!--begin::details View-->
        <div class="card mb-2" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Sales Details</h3>

                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                {{-- <a href="{{ route('admin.sales-order.edit', @$sales->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit</a> --}}
                <!--end::Action-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show" id="purchaseDetailsCollapse">
                <div class="card-body p-9">

                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Invoice Number</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ @$sales->invoice_number }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Warehouse</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ @$sales->warehouse->name}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Store</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$sales->store->store_name }}</span>
                        </div>
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Delivery Date</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$sales->delivered_date }}</span>
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
