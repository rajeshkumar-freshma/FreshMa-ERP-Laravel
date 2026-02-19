    <!--begin::Card-->

    <!--begin::Content container-->
    <div id="kt_app_content_container">
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body pt-3">
                <!--begin::Row-->
                <div class="row gy-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-sm-6 col-xl-2 text-muted  mb-xl-10">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 gradient-bg-darkgray">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 rounded p-2 bg-white">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--end::Icon-->
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <p class="t-c-w fs-4">Total Sales Orders:</p>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span
                                        class="fw-semibold fs-1 text-white lh-1 ls-n2">{{ number_format(@$sales_orders_count, 2) }}</span>
                                    <!--end::Number-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-sm-6 col-xl-2 text-muted  mb-xl-10">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 gradient-bg-green-to-teal">
                            <!--begin::Body-->
                            <div class="card-body d-flex  justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 rounded p-2 bg-white">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--end::Icon-->
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <p class="t-c-w  fs-4 fs-4">Total Sales Amount</p>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span
                                        class="fw-semibold fs-1 text-white lh-1 ls-n2">{{ number_format(@$sales_orders_total_amount, 2) }}</span>
                                    <!--end::Number-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-sm-6 col-xl-2 text-muted  mb-xl-10">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 gradient-bg-blue-to-purple">
                            <!--begin::Body-->
                            <div
                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 rounded p-2 bg-white">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--end::Icon-->
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <p class="t-c-w fs-4"> Purchase Orders Count</p>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span
                                        class="fw-semibold fs-1 text-white lh-1 ls-n2">{{ number_format(@$Pruchase_orders_count, 2) }}</span>
                                    <!--end::Number-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-sm-6 col-xl-2 text-muted  mb-xl-10">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 gradient-bg-red-to-orange">
                            <!--begin::Body-->
                            <div
                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 rounded p-2 bg-white">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--end::Icon-->
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <p class="t-c-w fs-4"> Purchase Orders Amount</p>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span
                                        class="fw-semibold fs-1 text-white lh-1 ls-n2">{{ number_format(@$Pruchase_orders_total_amount, 2) }}</span>
                                    <!--end::Number-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-sm-6 col-xl-2 mb-5 text-muted mb-xl-10">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 gradient-bg-blue-to-teal">
                            <!--begin::Body-->
                            <div
                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 rounded p-2 bg-white">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--end::Icon-->
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <p class="t-c-w fs-4">Total Vendor</p>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span
                                        class="fw-semibold fs-1 text-white lh-1 ls-n2">{{ @$customer_count}}</span>
                                    <!--end::Number-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-sm-6 col-xl-2 mb-5 text-muted mb-xl-10">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 bg-mono-circle">
                            <!--begin::Body-->
                            <div
                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 rounded p-2 bg-white">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <p class="t-c-w fs-4">Total Supplier</p>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span
                                        class="fw-semibold fs-1 text-white lh-1 ls-n2">{{ @$supplier_count}}</span>
                                    <!--end::Number-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                    </div>
                    <!--end::Col-->
                </div>
                <div class="row gy-5 g-xl-10">
                    <div class="col-sm-6 col-xl-6 col-md-6">
                        <div class="card gradient-bg-darkgray">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-white">Sales And Purchase Order Chart</span>
                                    <span class="t-c-w mt-1 fw-semibold fs-6">Delivered Count</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5 ">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_38_chart1"
                                    class="h-325px w-100 min-h-auto ps-4 pe-6">
                                </div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6 col-md-6">
                        <div class="card gradient-bg-green-to-teal">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-white">Income/Expense Chart</span>
                                    <span class="t-c-w mt-1 fw-semibold fs-6">Total Amount</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_38_chart2" class="h-325px w-100 min-h-auto ps-4 pe-6">
                                </div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6 col-md-6">
                        <div class="card gradient-bg-blue-to-purple">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-white">Branch Wise Sales Orders</span>
                                    <span class="t-c-w mt-1 fw-semibold fs-6">Count</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_38_chart3" data-kt-chart-info="Product Transfer"
                                    class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6 col-md-6">
                        <div class="card gradient-bg-red-to-orange">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-white">Branch Wise
                                        Income/Expense</span>
                                    <span class="t-c-w mt-1 fw-semibold fs-6">Count</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_38_chart5" data-kt-chart-info="Product Transfer"
                                    class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6 col-md-6">
                        <div class="card gradient-bg-blue-to-teal">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-white">Branch Wise Product Transfer</span>
                                    <span class="t-c-w mt-1 fw-semibold fs-6">Count</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_38_chart4" class="h-325px w-100 min-h-auto ps-4 pe-6">
                                </div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Card-->
