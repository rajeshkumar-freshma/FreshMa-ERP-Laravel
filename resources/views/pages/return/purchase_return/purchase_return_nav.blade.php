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
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Purchase Return
            </h1>
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
                        <h3>Purchase Return Details</h3>
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->

                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Purchase Return ID:</span>
                                    <span class="text-hover-primary fs-4 fw-bold"
                                        style="color: green;">{{ @$purchase_return->purchase_order_return_number }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Status:</span>
                                    @include('pages.partials.statuslabel', [
                                        'indent_status' => @$purchase_return->status,
                                    ])
                                    <span class="fw-bold fs-4 text-muted me-1 px-10">Payment Status:</span>
                                    @include('pages.partials.statuslabel', [
                                        'payment_status' => @$purchase_return->payment_status,
                                    ])
                                </div>
                                <!--end::Name-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$purchase_return->total_amount }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Purchase Return Amount</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    {{-- <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$purchase_return->return_order_transactions->sum('amount') ?? 0 }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Payment Transaction Amount</div>
                                        <!--end::Label-->
                                    </div> --}}

                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$purchase_return->total_expense_amount }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Expense Amount</div>
                                        <!--end::Label-->
                                    </div>

                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$purchase_return->total_expense_billable_amount }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Expense Bill Amount</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Stats-->
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
                        <h3>Supplier Details</h3>
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Supplier Name:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1"
                                        style="color: green;">{{ @$purchase_return->supplier->first_name ?? (@$purchase_return->supplier->last_name ?? '-') }}

                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Supplier Email:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1"
                                        style="color: green;">{{ @$purchase_return->supplier->email ?? '-' }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Supplier Code:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1"
                                        style="color: green;">{{ @$purchase_return->supplier->user_code ?? '-' }}</span>
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
                        <a class="nav-link text-active-primary ms-0 me-10 py-5  {{ request()->routeIs('admin.purchase-return.show') ? 'active' : '' }}"
                            href="{{ route('admin.purchase-return.show', @$purchase_return->id) }}">Overview</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.productpurchasereturn_data') ? 'active' : '' }}"
                            href="{{ route('admin.productpurchasereturn_data', @$purchase_return->id) }}">Product</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.purchasereturntransport_data') ? 'active' : '' }}"
                            href="{{ route('admin.purchasereturntransport_data', @$purchase_return->id) }}">Transport Details</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.purchasereturnnexpences_data') ? 'active' : '' }}"
                            href="{{ route('admin.purchasereturnnexpences_data', @$purchase_return->id) }}">Expense Details</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.purchasereturnpayment_data') ? 'active' : '' }}"
                            href="{{ route('admin.purchasereturnpayment_data', @$purchase_return->id) }}">Payment Details</a>
                    </li>
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
