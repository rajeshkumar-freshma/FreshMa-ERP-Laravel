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
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Sales Return
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
                        <h3>Sales Return Details</h3>
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->

                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Sales Return ID:</span>
                                    <span class="text-hover-primary fs-4 fw-bold"
                                        style="color: green;">{{ @$sales_return->sales_order_return_number }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Status:</span>
                                    @include('pages.partials.statuslabel', [
                                        'indent_status' => @$sales_return->status,
                                    ])
                                    <span class="fw-bold fs-4 text-muted me-1 px-5">Payment Status:</span>
                                    @include('pages.partials.statuslabel', [
                                        'payment_status' => @$sales_return->payment_status,
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
                                                data-kt-countup-value="{{ @$sales_return->total_amount }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Sales Return Amount</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$sales_return->sales_return_transactions->sum('amount') ?? 0 }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Paid Amount</div>
                                        <!--end::Label-->
                                    </div>
                                    @php
                                        // Calculate the sum of amounts from purchase order transactions, default to 0 if none exist
                                        $sales_return_amount = $sales_return->sales_return_transactions->sum('amount') ?? 0;

                                        // Calculate the pending amount
                                        $pending_amount = $sales_return->total_amount - $sales_return_amount;
                                    @endphp
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$sales_return->sales_return_transactions->sum('amount') ?? 0 }}"
                                                data-kt-countup-prefix="₹">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Pending Amount</div>
                                        <!--end::Label-->
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold" data-kt-countup="true"
                                                data-kt-countup-value="{{ @$sales_return->expense_details->sum('ie_amount') }}"
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
                                                data-kt-countup-value="{{ @$sales_return->total_expense_billable_amount }}"
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
                        <h3>Vendor Details</h3>
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Vendor Name:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1"
                                        style="color: green;">{{ @$sales_return->from_vendor->first_name ?? (@$sales_return->from_vendor->last_name ?? '-') }}

                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Vendor Email:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1"
                                        style="color: green;">{{ @$sales_return->from_vendor->email ?? '-' }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold fs-4 text-muted me-1">Vendor Code:</span>
                                    <span class="text-hover-primary fs-4 fw-bold me-1"
                                        style="color: green;">{{ @$sales_return->from_vendor->user_code ?? '-' }}</span>
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
                        <a class="nav-link text-active-primary ms-0 me-10 py-5  {{ request()->routeIs('admin.sales-return.show') ? 'active' : '' }}"
                            href="{{ route('admin.sales-return.show', @$sales_return->id) }}">Overview</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.productsalesreturn_data') ? 'active' : '' }}"
                            href="{{ route('admin.productsalesreturn_data', @$sales_return->id) }}">Product</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.transportreturn_data') ? 'active' : '' }}"
                            href="{{ route('admin.transportreturn_data', @$sales_return->id) }}">Transport Details</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.returnexpences_data') ? 'active' : '' }}"
                            href="{{ route('admin.returnexpences_data', @$sales_return->id) }}">Expense Details</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.returnpayment_data') ? 'active' : '' }}"
                            href="{{ route('admin.returnpayment_data', @$sales_return->id) }}">Payment Details</a>
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
