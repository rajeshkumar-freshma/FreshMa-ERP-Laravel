<div class="card-body pt-9 pb-0">
    <!--begin::Details-->
    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
        <!--begin: Pic-->
        <div class="me-7 mb-4">
            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                <img src="{{$product->image_full_url}}" alt="image" />
            </div>
        </div>
        <!--end::Pic-->
        <!--begin::Info-->
        <div class="flex-grow-1">
            <!--begin::Title-->
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                <!--begin::User-->
                <div class="d-flex flex-column">
                    <!--begin::Name-->
                    <div class="d-flex align-items-center mb-2">
                        <span class="text-hover-primary fs-2 fw-bold me-1" style="color: green;">{{$product->name}}</span>
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
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{$purchase_amount}}" data-kt-countup-prefix="₹">0</div>
                            </div>
                            <!--end::Number-->
                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 text-gray-400">Purchase Order Amount</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Stat-->
                        <!--begin::Stat-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{$sales_amount}}" data-kt-countup-prefix="₹">0</div>
                            </div>
                            <!--end::Number-->
                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 text-gray-400">Sales Order Amount</div>
                            <!--end::Label-->
                        </div>
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{$warehouse_amount}}" data-kt-countup-prefix="₹">0</div>
                            </div>
                            <!--end::Number-->
                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 text-gray-400">Warehouse Indent Request Amount</div>
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
    <!--end::Details-->
    <!--begin::Navs-->
    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
        <!--begin::Nav item-->
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.product.show') ? 'active' : '' }}" href="{{ route('admin.product.show', $product->id) }}">Product Overview</a>
        </li>
        <!--end::Nav item-->
        <!--begin::Nav item-->
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.product_purchase') ? 'active' : '' }}" href="{{ route('admin.product_purchase', $product->id) }}">Product Purchase Order</a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.product_sales') ? 'active' : '' }}" href="{{ route('admin.product_sales', $product->id) }}">Product Sales Order</a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.product_store_intent_request') ? 'active' : '' }}" href="{{ route('admin.product_store_intent_request', $product->id) }}">Store Indent Request</a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.product_warehouse_intent_request') ? 'active' : '' }}" href="{{ route('admin.product_warehouse_intent_request', $product->id) }}">Warehouse Indent Request</a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.store_stock') ? 'active' : '' }}" href="{{ route('admin.store_stock', $product->id) }}">Store Stock</a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ request()->routeIs('admin.warehouse_stock') ? 'active' : '' }}" href="{{ route('admin.warehouse_stock', $product->id) }}">Warehouse Stock</a>
        </li>
        <!--end::Nav item-->
    </ul>
    <!--begin::Navs-->
</div>
