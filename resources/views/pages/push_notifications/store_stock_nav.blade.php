<style>
    .vertical-border {
        width: 1px;
        background-color: green;
        /* Adjust border color as needed */
        height: 100%;
        /* Adjust height according to your content */
    }
</style>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Daily Store Stock Updated Order</h1>
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
<!--begin::Content-->
<div class="card">
    <div class="card-body pt-9 pb-0">
        <!--begin::Details-->
        <div class="row">
            <!-- Left Side -->
            <div class="col-md-7">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <!--begin: Pic-->
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <h3>Daily Store Stock Updated Details</h3>
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->

                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Sales Order Number:</span>
                                    {{-- <span class="text-hover-primary fs-4 fw-bold" style="color: green;">{{ $store_stock_detail->invoice_number }}</span> --}}
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Status:</span>
                                    @include('pages.partials.statuslabel', ['indent_status'  ])
                                    <span class="fw-bold fs-4 text-muted me-1 px-10">Payment Status:</span>
                                    @include('pages.partials.statuslabel', ['payment_status'  ])
                                </div>
                                <!--end::Name-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Info-->
                </div>
            </div>
            <div class="col-md-1">
                <div class="vertical-border"></div>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <!--begin: Pic-->

                    <!--end::Pic-->
                    <!--begin::Info-->

                    <div class="flex-grow-1">
                        <h3>Create By Details</h3>
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Customer Name:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1" style="color: green;"></span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Customer Email:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1" style="color: green;"></span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Customer Code:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1" style="color: green;"></span>
                                </div>
                                <!--end::Name-->
                            </div>
                        </div>
                        <!--end::Title-->

                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
            </div>
            <!-- Right Side -->
            <div class="col-md-12">
                <!--begin::Navs-->
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-wrap">
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5  {{ request()->routeIs('admin.daily-stock-update.show') ? 'active' : '' }}" href="{{ route('admin.daily-stock-update.show', $store_stock_detail->id) }}">Overview</a>
                    </li>
                    {{-- <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.daily-stock-update') ? 'active' : '' }}" href="{{ route('admin.actual_stock', $store_stock_detail->id) }}">Actual Stock</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.daily-stock-update') ? 'active' : '' }}" href="{{ route('admin.poduct_ineventory_details', $store_stock_detail->id) }}">Product Inventory</a>
                    </li> --}}
                    <!--end::Nav item-->
                    <!--begin::Nav item-->

                    <!--end::Nav item-->
                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <!--end::Details-->
    </div>
</div>
