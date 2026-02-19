<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        {{-- <div class="card mb-5 mb-xl-10">
            @include('pages.store.daily_stock_updated.store_stock_nav')
        </div> --}}
        <!--end::Navbar-->
        <!--begin::details View-->
        <div class="card mb-2" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Daily Stock Update Details</h3>

                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                <a href="{{ route('admin.daily-stock-update.edit', $store_stock_detail->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit</a>
                <!--end::Action-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show" id="dailyStockDetailsCollapse">
                <div class="card-body p-9">

                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Product Name</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $store_stock_detail->product_details->name }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Store</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $store_stock_detail->store_details->store_name}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->


                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Stock Update On</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $store_stock_detail->stock_update_on }}</span>
                        </div> <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Opening  Stock</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $store_stock_detail->opening_stock }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Closing  Stock</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $store_stock_detail->closing_stock }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Usage  Stock</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $store_stock_detail->usage_stock }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::details View-->
        </div>
        <!--end::Content container-->
        @include('pages.partials.form_footer', ['show_reset' => false, 'show_save' => false, 'is_save' => false, 'back_url' => route('admin.daily-stock-update.index')])
    </div>
    </div>
</x-default-layout>
