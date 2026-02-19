<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
            data-kt-menu="true" data-kt-menu-expand="false">
            @can('Dashboard View')
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.Dashboard') ? 'here show active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endcan
            @if (auth()->user()->can('Warehouse View') ||
                    auth()->user()->can('Store View') ||
                    auth()->user()->can('Item Type View') ||
                    auth()->user()->can('Unit View') ||
                    auth()->user()->can('Denomination View') ||
                    auth()->user()->can('Income/Expense Category View') ||
                    auth()->user()->can('Tax Rate View') ||
                    auth()->user()->can('Partnership Type View') ||
                    auth()->user()->can('Customer View') ||
                    auth()->user()->can('Category View') ||
                    auth()->user()->can('Partner/Manager View') ||
                    auth()->user()->can('Supplier View') ||
                    auth()->user()->can('Transport Type View') ||
                    auth()->user()->can('Machine Details View') ||
                    auth()->user()->can('Payment Type View'))
                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Master</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->is('*/master/*') ? 'here show active' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link {{ request()->is('*/master/*') ? 'active' : '' }}">
                        <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                        <span class="menu-title">Master</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth()->user()->can('Warehouse View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.warehouse.*') ? 'active' : '' }}"
                                    href="{{ route('admin.warehouse.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Warehouse</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Store View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.store.*') ? 'active' : '' }}"
                                    href="{{ route('admin.store.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Store</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Item Type View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.item-type.*') ? 'active' : '' }}"
                                    href="{{ route('admin.item-type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Item Type</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Unit View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.unit.*') ? 'active' : '' }}"
                                    href="{{ route('admin.unit.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Unit</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Denomination View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.denomination-type.*') ? 'active' : '' }}"
                                    href="{{ route('admin.denomination-type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Denomination Type</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Income/Expense Category View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.income-expense-type.*') ? 'active' : '' }}"
                                    href="{{ route('admin.income-expense-type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Income/Expense Type</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Tax Rate View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.tax-rate.*') ? 'active' : '' }}"
                                    href="{{ route('admin.tax-rate.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Tax Rate</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Partnership Type View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.partnership-type.*') ? 'active' : '' }}"
                                    href="{{ route('admin.partnership-type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Partnership Type</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Customer View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}"
                                    href="{{ route('admin.customer.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Vendor</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Category View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}"
                                    href="{{ route('admin.category.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Category</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Partner/Manager View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.partner.*') ? 'active' : '' }}"
                                    href="{{ route('admin.partner.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Partner/Manager</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Supplier View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.supplier.*') ? 'active' : '' }}"
                                    href="{{ route('admin.supplier.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Supplier</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Transport Type View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.transport-type.*') ? 'active' : '' }}"
                                    href="{{ route('admin.transport-type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Transport Type</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Machine Details View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.machine-details.*') ? 'active' : '' }}"
                                    href="{{ route('admin.machine-details.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Machine Details</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                        @if (auth()->user()->can('Payment Type View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.payment-type.*') ? 'active' : '' }}"
                                    href="{{ route('admin.payment-type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Payment Type</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif

                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
            @endif
            @if (auth()->user()->can('Products View') ||
                    auth()->user()->can('Stock Management View') ||
                    auth()->user()->can('Adjustment View') ||
                    auth()->user()->can('Fish Cutting View') ||
                    auth()->user()->can('Product Fish Cutting Mapping View') ||
                    auth()->user()->can('Daily Product Price Update View') ||
                    auth()->user()->can('Store Indent Request View') ||
                    auth()->user()->can('Vendor/Customer Indent Request View') ||
                    auth()->user()->can('Warehouse Indent Request View') ||
                    auth()->user()->can('Purchase Order View') ||
                    auth()->user()->can('Product Pin Mapping View') ||
                    auth()->user()->can('Purchase Credit Notes View'))
                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">PRODUCT</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.product.*') || request()->routeIs('admin.stock-management.*') || request()->routeIs('admin.adjustment.*') || request()->routeIs('admin.fish-cutting.*') || request()->routeIs('admin.fish-cutting-product-map.*') || request()->routeIs('admin.fish-price-update.*') ? 'here show active' : '' }}">
                    <!--begin:Menu link-->
                    <span
                        class="menu-link {{ request()->routeIs('admin.product.*') || request()->routeIs('admin.stock-management.*') || request()->routeIs('admin.adjustment.*') || request()->routeIs('admin.fish-cutting.*') || request()->routeIs('admin.fish-cutting-product-map.*') || request()->routeIs('admin.fish-price-update.*') ? 'here show active' : '' }}">
                        <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                        <span class="menu-title">Products</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth()->user()->can('Products View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}"
                                    href="{{ route('admin.product.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Products</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                        @if (auth()->user()->can('Stock Management View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.stock-management.*') ? 'active' : '' }}"
                                    href="{{ route('admin.stock-management.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Stock Management View</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                        @if (auth()->user()->can('Adjustment View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.adjustment.*') ? 'active' : '' }}"
                                    href="{{ route('admin.adjustment.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Adjustment</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                        @if (auth()->user()->can('Product Fish Cutting Mapping View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.fish-cutting-product-map.*') ? 'active' : '' }}"
                                    href="{{ route('admin.fish-cutting-product-map.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Product Fish Cutting Mapping</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                        @if (auth()->user()->can('Fish Cutting View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('admin.fish-cutting.*') ? 'active' : '' }}"
                                    href="{{ route('admin.fish-cutting.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Fish Cutting</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                        @if (auth()->user()->can('Daily Product Price Update View'))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.fish-price-update.*') ? 'active' : '' }}"
                                    href="{{ route('admin.fish-price-update.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Daily Product Price Update</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                    </div>
                    <!--end:Menu sub-->

                </div>

                <!--begin:Menu item-->
                {{-- <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('admin.quotations.*') ? 'here show active' : '' }}" href="#">
            <span class="menu-icon">{!! getIcon('rocket', 'fs-2') !!}</span>
            <span class="menu-title">Quotations</span>
            </a>
            <!--end:Menu link-->
        </div> --}}
                <!--end:Menu item-->

                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->is('*-indent-request*') ? 'here show active' : '' }}">
                    @if (auth()->user()->can('Store Indent Request View'))
                        <!--begin:Menu link-->
                        <span class="menu-link {{ request()->routeIs('admin.*-indent-request.*') ? 'active' : '' }}">
                            <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                            <span class="menu-title">Indent Request</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->

                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.store-indent-request.*') ? 'active' : '' }}"
                                    href="{{ route('admin.store-indent-request.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Store Indent Request</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Customer Indent Request View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.customer-indent-request.*') ? 'active' : '' }}"
                                href="{{ route('admin.customer-indent-request.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Vendor Indent Request</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Warehouse Indent Request View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.warehouse-indent-request.*') ? 'active' : '' }}"
                                href="{{ route('admin.warehouse-indent-request.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Warehouse Indent Request</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    {{-- @if (Auth::user()->hasPermissionTo('supplier order request - view')) --}}
                    <!--begin:Menu item-->
                    {{-- <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.adjustment.*') ? 'active' : '' }}"
                            href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Order Request</span>
                        </a>
                        <!--end:Menu link-->
                    </div> --}}
                    {{-- @endcan --}}
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
        </div>

        <div data-kt-menu-trigger="click"
            class="menu-item menu-accordion {{ request()->routeIs('admin.purchase-order.*') || request()->routeIs('sales-order.*') || request()->routeIs('admin.purchase-credit.*') || request()->routeIs('admin.productmapping') ? 'here show active' : '' }}">
            <!--begin:Menu link-->
            <span
                class="menu-link {{ request()->routeIs('admin.purchase-order.*') || request()->routeIs('sales-order.*') || request()->routeIs('admin.purchase-credit.*') || request()->routeIs('admin.product-pin-mapping.*') ? 'active' : '' }}">
                <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                <span class="menu-title">Purchase Order</span>
                <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
                @if (auth()->user()->can('Purchase Order View'))
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.purchase-order.*') ? 'active' : '' }}"
                            href="{{ route('admin.purchase-order.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Purchase Order</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                @endif

                {{-- @if (auth()->user()->can('Product Pin Mapping View'))
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.productmapping') ? 'active' : '' }}"
                            href="{{ route('admin.productmapping') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Product Pin Mapping</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                @endif --}}


                <!--begin:Menu item-->
                {{-- @if (Auth::user()->hasRole('supplier')) --}}
                {{-- <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('sales-order.*') ? 'active' : '' }}"
                            href="{{ route('sales-order.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Sales Order</span>
                        </a>
                        <!--end:Menu link-->
                    </div> --}}
                {{-- @endif --}}
                <!--end:Menu item-->
                @if (auth()->user()->can('Purchase Credit Notes View'))
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.purchase-credit.*') ? 'active' : '' }}"
                            href="{{ route('admin.purchase-credit.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Purchase Credit Notes</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                @endif
            </div>
            <!--end:Menu sub-->
        </div>
        <!--end:Menu item-->
        @endif
        <!--begin:Menu item-->
        @if (auth()->user()->can('Sales Order View') ||
                auth()->user()->can('Stock Management View') ||
                auth()->user()->can('Sales Credit View') ||
                auth()->user()->can('Income Expense View'))
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Sales</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.store-sales.*') || request()->routeIs('admin.sales-order.*') || request()->routeIs('admin.sales-credit.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.store-sales.*') || request()->routeIs('admin.sales-order.*') || request()->routeIs('admin.sales-credit.*') || request()->routeIs('admin.sales-credit.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Sales</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">POS Sales</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.stock-management.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Online Sales</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                    @if (auth()->user()->can('Sales Order View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.sales-order.*') ? 'active' : '' }}"
                                href="{{ route('admin.sales-order.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sales Order</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif
                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.store-sales.*') ? 'active' : '' }}" href="{{ route('admin.store-sales.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Store Sales</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                    @if (auth()->user()->can('Sales Credit View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.sales-credit.*') ? 'active' : '' }}"
                                href="{{ route('admin.sales-credit.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sales Credit</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
            {{-- income and expnse adding  --}}

            @if (auth()->user()->can('Income Expense Add View'))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.income-and-expense.*') ? 'here show active' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link {{ request()->routeIs('admin.income-and-expense.*') ? 'active' : '' }}">
                        <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                        <span class="menu-title">Income And Expense</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.income-and-expense.*') ? 'active' : '' }}"
                                href="{{ route('admin.income-and-expense.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Income Expense Add</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
            @endif
            <!--end:Menu item-->
        @endif
        {{-- Supplier Addvanced menu --}}
        @if (auth()->user()->can('Supplier Payment View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Supplier Bulk Payment</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.supplier-bulk-payment.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.supplier-bulk-payment.*') ? 'here show active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Supplier Bulk Payment</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.supplier-bulk-payment.*') ? 'active' : '' }}"
                            href="{{ route('admin.supplier-bulk-payment.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Supplier Bulk Payment</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif
        @if (auth()->user()->can('Cash Paind To Offiice View') ||
                auth()->user()->can('Cash Register View') ||
                auth()->user()->can('Daily Stock Update View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Store Cash And Register</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.store-cash.*') || request()->routeIs('admin.cash-paid-office.*') || request()->routeIs('admin.cash-register.*') || request()->routeIs('admin.daily-stock-update.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.store-cash.*') || request()->routeIs('admin.cash-paid-office.*') || request()->routeIs('admin.cash-register.*') || request()->routeIs('admin.daily-stock-update.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Store</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <div class="menu-sub menu-sub-accordion">
                    @if (auth()->user()->can('Cash Paind To Offiice View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.cash-paid-office.*') ? 'active' : '' }}"
                                href="{{ route('admin.cash-paid-office.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Cash Paid To Office</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Cash Register View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.cash-register.*') ? 'active' : '' }}"
                                href="{{ route('admin.cash-register.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Cash Register</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Daily Stock Update View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.daily-stock-update.*') ? 'active' : '' }}"
                                href="{{ route('admin.daily-stock-update.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daily Stock Update</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif
                </div>

            </div>
            <!--end:Menu item-->
        @endif
        @if (auth()->user()->can('Purchase Return View') ||
                auth()->user()->can('Sales Return View') ||
                auth()->user()->can('Product Transfer View') ||
                auth()->user()->can('Bulk Product Transfer View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Returns</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.purchase-return.*') || request()->routeIs('admin.sales-return.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.purchase-return.*') || request()->routeIs('admin.sales-return.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Returns</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    @if (auth()->user()->can('Purchase Return View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.purchase-return.*') ? 'active' : '' }}"
                                href="{{ route('admin.purchase-return.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Purchase Return</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Sales Return View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.sales-return.*') ? 'active' : '' }}"
                                href="{{ route('admin.sales-return.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sales Return</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
            @if (auth()->user()->can('Product Transfer View'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.product-transfer.*') ? 'here show active' : '' }}"
                        href="{{ route('admin.product-transfer.index') }}">
                        <span class="menu-icon">{!! getIcon('rocket', 'fs-2') !!}</span>
                        <span class="menu-title">Product Transfer</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif

            @if (auth()->user()->can('Bulk Product Transfer View'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.bulk-product-transfer.*') ? 'here show active' : '' }}"
                        href="{{ route('admin.bulk-product-transfer.index') }}">
                        <span class="menu-icon">{!! getIcon('rocket', 'fs-2') !!}</span>
                        <span class="menu-title">Bulk Product Transfer</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
        @endif
        {{-- <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Asset Management</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('admin.asset-management.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link {{ request()->routeIs('admin.asset-management.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Asset Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.assets.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Asset</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.type.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Type</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.licences.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Licences</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.accessories.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Accessories</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.consumables.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Consumables</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.depreciation.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Depreciation</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.asset-management.maintanance.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Maintanance</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--End:Menu item--> --}}
        @if (auth()->user()->can('Accounts View') ||
                auth()->user()->can('Transfer View') ||
                auth()->user()->can('Transaction View') ||
                auth()->user()->can('Transaction Report View') ||
                auth()->user()->can('Bulk Transaction Upload View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Accounting</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.accounting.*') || request()->routeIs('admin.transfer.*') || request()->routeIs('admin.transaction.*') || request()->routeIs('admin.upload-transactions.*') || request()->routeIs('admin.accounts.*') || request()->routeIs('admin.bank-transactions-report.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.accounting.*') || request()->routeIs('admin.transfer.*') || request()->routeIs('admin.transaction.*') || request()->routeIs('admin.upload-transactions.*') || request()->routeIs('admin.accounts.*') || request()->routeIs('admin.bank-transactions-report.*') ? 'here show active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Accounting</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.accounts.type.*') ? 'active' : '' }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Accounting Type</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                    @if (auth()->user()->can('Accounts View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}"
                                href="{{ route('admin.accounts.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Accounts</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Transfer View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.transfer.*') ? 'active' : '' }}"
                                href="{{ route('admin.transfer.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Transfer</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.accounting.budgeting.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Budgeting</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    @if (auth()->user()->can('Transaction View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.transaction.*') ? 'active' : '' }}"
                                href="{{ route('admin.transaction.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Transaction</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Transaction Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.bank-transactions-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.bank-transactions-report.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Transactions Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Bulk Transaction Upload View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.upload-transactions.*') ? 'active' : '' }}"
                                href="{{ route('admin.upload-transactions.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Bulk Transaction Upload</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                </div>
                <!--end:Menu sub-->
            </div>
            <!--End:Menu item-->
        @endif
        @if (auth()->user()->can('Department View') ||
                auth()->user()->can('Desigination View') ||
                auth()->user()->can('Employee View') ||
                auth()->user()->can('Staff Attendance View') ||
                auth()->user()->can('Staff Advanced View') ||
                auth()->user()->can('Leave Type View') ||
                auth()->user()->can('Holiday View') ||
                auth()->user()->can('Leave View') ||
                auth()->user()->can('Payroll Type View') ||
                auth()->user()->can('Payroll Template View') ||
                auth()->user()->can('Payroll Setup View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">HR Management</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->is('*/hrm*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link {{ request()->routeIs('admin.hrm.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">HRM</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">

                    @if (auth()->user()->can('Department View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a
                                class="menu-link {{ request()->routeIs('admin.department.*') ? 'active' : '' }}"href="{{ route('admin.department.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Departments</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Desigination View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.designation.*') ? 'active' : '' }}"
                                href="{{ route('admin.designation.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Designation</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Employee View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}"
                                href="{{ route('admin.employee.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Employee</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.hrm.office-shift.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Office Shift</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    @if (auth()->user()->can('Staff Attendance View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.staff_attendance.*') ? 'active' : '' }}"
                                href="{{ route('admin.staff_attendance.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Staff Attendance</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    @if (auth()->user()->can('Staff Advanced View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.staff-advance.*') ? 'active' : '' }}"
                                href="{{ route('admin.staff-advance.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Staff Advance</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    @if (auth()->user()->can('Leave Type View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.leave_type.*') ? 'active' : '' }}"
                                href="{{ route('admin.leave_type.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Leave Type</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    @if (auth()->user()->can('Holiday View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.holiday.*') ? 'active' : '' }}"
                                href="{{ route('admin.holiday.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Holiday</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Leave View'))
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.leave.*') ? 'active' : '' }}"
                                href="{{ route('admin.leave.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Leave</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endif

                    @if (auth()->user()->can('Payroll Type View'))
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.pay-roll-type.*') ? 'active' : '' }}"
                                href="{{ route('admin.pay-roll-type.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Payroll Type</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endif

                    @if (auth()->user()->can('Payroll Template View'))
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.pay-roll-template.*') ? 'active' : '' }}"
                                href="{{ route('admin.pay-roll-template.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Payroll Template</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endif

                    @if (auth()->user()->can('Payroll Setup View'))
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}"
                                href="{{ route('admin.payroll.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Payroll Setup</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif
                    {{--    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.hrm.pay-roll.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Pay Roll</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.hrm.payslip.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Pay Slip</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                </div>
                <!--end:Menu sub-->
            </div>
            <!--End:Menu item-->
        @endif
        {{-- <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Income/Expense</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('admin.income-expense.income.*') ? 'active' : '' }}" href="#">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Income</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('admin.income-expense.expense.*') ? 'active' : '' }}" href="#">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Expense</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item--> --}}
        @if (auth()->user()->can('Apply Loan View') ||
                auth()->user()->can('RePayment View') ||
                auth()->user()->can('Loan Products View') ||
                auth()->user()->can('Loan Charges View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Loan Management</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.loan-management.*') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.loan-repayment.*') || request()->routeIs('admin.loan-categories.*') || request()->routeIs('admin.loan-charges.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.loan-management.*') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.loan-repayment.*') || request()->routeIs('admin.loan-categories.*') || request()->routeIs('admin.loan-charges.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Loan Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.loan-management.payments.*') ? 'active' : '' }}"
                            href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">All Loans</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    @if (auth()->user()->can('Apply Loan View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}"
                                href="{{ route('admin.loans.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Apply Loan</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.loan-management.payments.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Active Loans</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                     <!--begin:Menu item-->
                     <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.loan-management.payments.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Pending Loans</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    --}}
                    <!--end:Menu item-->

                    @if (auth()->user()->can('RePayment View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.loan-repayment.*') ? 'active' : '' }}"
                                href="{{ route('admin.loan-repayment.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Re Payments</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    @if (auth()->user()->can('Loan Products View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.loan-categories.*') ? 'active' : '' }}"
                                href="{{ route('admin.loan-categories.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Loan Product</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    @if (auth()->user()->can('Loan Charges View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.loan-charges.*') ? 'active' : '' }}"
                                href="{{ route('admin.loan-charges.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">charges</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif
        @if (auth()->user()->can('Transactions View') || auth()->user()->can('Suppliers View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Payments</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.payment-transaction-report.*') || request()->routeIs('admin.suppliers-report.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.payment-transaction-report.*') || request()->routeIs('admin.suppliers-report.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Payments</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">

                    @if (auth()->user()->can('Transactions View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.payment-transaction-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.payment-transaction-report.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Transactions</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Suppliers View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.suppliers-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.suppliers-report.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Suppliers</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.payments.vendor.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Vendor</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.payments.user.*') ? 'active' : '' }}"
                            href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">User</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.payments.collection.*') ? 'active' : '' }}"
                            href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Payment Collection</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif
        @if (auth()->user()->can('Branch Sales Report View') ||
                auth()->user()->can('Product Sales Report View') ||
                auth()->user()->can('Product Purchase Report View') ||
                auth()->user()->can('Supplier Wise Purchase Report View') ||
                auth()->user()->can('Daily Sales Report View') ||
                auth()->user()->can('Daily Store Report View') ||
                auth()->user()->can('Profit And Loss Report View') ||
                auth()->user()->can('Indent Request Report View') ||
                auth()->user()->can('Sales Orders Report View') ||
                auth()->user()->can('Fish Cutting Details Report View') ||
                auth()->user()->can('Payments Report View') ||
                auth()->user()->can('Employee Report View') ||
                auth()->user()->can('Store Stock Report View') ||
                auth()->user()->can('Product Warehouse Report View'))
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Reports</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.report.*') ||
                request()->routeIs('admin.branch-machine-sale.*') ||
                request()->routeIs('admin.productwisesalereport') ||
                request()->routeIs('admin.productwisepurchasereport') ||
                request()->routeIs('admin.supplier_wise_purchase_orders') ||
                request()->routeIs('admin.dailysalesreport') ||
                request()->routeIs('admin.dailystorereportdata') ||
                request()->routeIs('admin.profit_and_loss') ||
                request()->routeIs('admin.productwiseindentrequestreport') ||
                request()->routeIs('admin.salesorderreportdata') ||
                request()->routeIs('admin.fishcuttingdetailsreport') ||
                request()->routeIs('admin.payments_report') ||
                request()->routeIs('admin.employee_report') ||
                request()->routeIs('admin.store_stock_report') ||
                request()->routeIs('admin.warehouse_stock_report')
                    ? 'here show active'
                    : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.report.*') ||
                    request()->routeIs('admin.branch-machine-sale.*') ||
                    request()->routeIs('admin.productwisesalereport') ||
                    request()->routeIs('admin.productwisepurchasereport') ||
                    request()->routeIs('admin.supplier_wise_purchase_orders') ||
                    request()->routeIs('admin.dailysalesreport') ||
                    request()->routeIs('admin.dailystorereportdata') ||
                    request()->routeIs('admin.profit_and_loss') ||
                    request()->routeIs('admin.productwiseindentrequestreport') ||
                    request()->routeIs('admin.salesorderreportdata') ||
                    request()->routeIs('admin.fishcuttingdetailsreport') ||
                    request()->routeIs('admin.payments_report') ||
                    request()->routeIs('admin.employee_report') ||
                    request()->routeIs('admin.store_stock_report') ||
                    request()->routeIs('admin.warehouse_stock_report')
                        ? 'here show active'
                        : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Reports</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    @if (auth()->user()->can('Store Stock Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.store_stock_report') ? 'active' : '' }}"
                                href="{{ route('admin.store_stock_report') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Store Stock Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Product Warehouse Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.warehouse_stock_report') ? 'active' : '' }}"
                                href="{{ route('admin.warehouse_stock_report') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Warehouse Stock Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif
                    @if (auth()->user()->can('Branch Sales Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.branch-machine-sale.*') ? 'active' : '' }}"
                                href="{{ route('admin.branch-machine-sale.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Machine Sales Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Product Sales Report View'))
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.productwisesalereport') ? 'active' : '' }}"
                                href="{{ route('admin.productwisesalereport') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Product Sale Report</span>
                            </a>
                        </div>
                    @endif

                    @if (auth()->user()->can('Product Purchase Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.productwisepurchasereport') ? 'active' : '' }}"
                                href="{{ route('admin.productwisepurchasereport') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Product Purchase Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Supplier Wise Purchase Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.supplier_wise_purchase_orders') ? 'active' : '' }}"
                                href="{{ route('admin.supplier_wise_purchase_orders') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Supplier Wise Purchase Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Daily Sales Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.dailysalesreport') ? 'active' : '' }}"
                                href="{{ route('admin.dailysalesreport') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daily Sales Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Daily Store Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.dailystorereportdata') ? 'active' : '' }}"
                                href="{{ route('admin.dailystorereportdata') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daily Store Report </span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Profit And Loss Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.profit_and_loss') ? 'active' : '' }}"
                                href="{{ route('admin.profit_and_loss') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Profit And Loss Report </span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Indent Request Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.productwiseindentrequestreport') ? 'active' : '' }}"
                                href="{{ route('admin.productwiseindentrequestreport') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Indent Request Report </span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                    @if (auth()->user()->can('Sales Orders Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.salesorderreportdata') ? 'active' : '' }}"
                                href="{{ route('admin.salesorderreportdata') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sales Orders Report </span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Fish Cutting Details Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.fishcuttingdetailsreport') ? 'active' : '' }}"
                                href="{{ route('admin.fishcuttingdetailsreport') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Fish Cutting Details Report </span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Payments Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.payments_report') ? 'active' : '' }}"
                                href="{{ route('admin.payments_report') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Payments Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Employee Report View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.employee_report') ? 'active' : '' }}"
                                href="{{ route('admin.employee_report') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Employee Report</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif



                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.payments.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Payment</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.expense-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Expense Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->



                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.profit-loss-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Profit/Loss Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.sales-purchase-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Sales vs Purchase</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.customer-payment-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Vendor payment Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.supplier-payment-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Supplier payment Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.vendor-payment-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Vendor payment Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.agent-payment-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Agent payment Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.stock-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Stock Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.warehouse-product-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Product Warehouse Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.report.payment-report.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Payment Report</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif

        @if (auth()->user()->can('App Menu Mapping View') ||
                auth()->user()->can('System Setting View') ||
                auth()->user()->can('Email Template View') ||
                auth()->user()->can('SMTP View'))
            <!--begin:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Setting</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.setting.*') ||
                request()->routeIs('admin.app-menu-mapping.*') ||
                request()->routeIs('admin.push-notification.*') ||
                request()->routeIs('admin.system-setting.*') ||
                request()->routeIs('admin.api-keys.*') ||
                request()->routeIs('admin.mail-setting.*') ||
                request()->routeIs('admin.email-template.*')
                    ? 'here show active'
                    : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.setting.*') ||
                    request()->routeIs('admin.app-menu-mapping.*') ||
                    request()->routeIs('admin.push-notification.*') ||
                    request()->routeIs('admin.system-setting.*') ||
                    request()->routeIs('admin.api-keys.*') ||
                    request()->routeIs('admin.mail-setting.*') ||
                    request()->routeIs('admin.email-template.*')
                        ? 'active'
                        : '' }}">


                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Setting</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    @if (auth()->user()->can('App Menu Mapping View'))
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.app-menu-mapping.*') ? 'active' : '' }}"
                                href="{{ route('admin.app-menu-mapping.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">App Menu Mapping</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('System Setting View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.system-setting.*') ? 'active' : '' }}"
                                href="{{ route('admin.system-setting.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">System Setting</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('Email Template View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.email-template.*') ? 'active' : '' }}"
                                href="{{ route('admin.email-template.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">E-mail Template</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    @if (auth()->user()->can('SMTP View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.mail-setting.*') ? 'active' : '' }}"
                                href="{{ route('admin.mail-setting.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">SMTP</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.setting.account-setting.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Account Setting</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->


                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.setting.sms-setting.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">SMS Setting</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.setting.apikey-setting.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">API Key</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.api-keys.*') ? 'active' : '' }}"
                            href="{{ route('admin.api-keys.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Api Keys</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.setting.pdf-setting.*') ? 'active' : '' }}"
                            href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">PDF</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}

                    {{-- <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.push-notification') ? 'active' : '' }}"
                            href="{{ route('admin.push-notification.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Notification</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> --}}
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif
        {{-- <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('admin.accounts.*') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">Accounts</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin.accounts.system-setting.*') ? 'active' : '' }}" href="#">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Bank Account</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item--> --}}
        @if (auth()->user()->can('Users List View') ||
                auth()->user()->can('Role View') ||
                auth()->user()->can('Assign Role To User View') ||
                auth()->user()->can('Activity Log View'))
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin.user-management.*') || request()->routeIs('admin.role-management.*') || request()->routeIs('admin.activitylog') ? 'here show active' : '' }}">
                <!--begin:Menu link-->
                <span
                    class="menu-link {{ request()->routeIs('admin.user-management.*') || request()->routeIs('admin.role-management.*') || request()->routeIs('admin.activitylog') ? 'active' : '' }}">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">User Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion mb-1 {{ request()->routeIs('admin.user-management.users.*') || request()->routeIs('admin.activitylog.*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Users</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">

                            @if (auth()->user()->can('Users List View'))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ request()->routeIs('admin.user-management.users.*') ? 'active' : '' }}"
                                        href="{{ route('admin.user-management.users.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Users List</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                            @endif
                            {{-- <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.user-management.users.show') ? 'active' : '' }}" href="{{ route('admin.user-management.users.show', 1) }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">View User</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item--> --}}
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('admin.role-management.roles.*') || request()->routeIs('admin.role-management.assign-role-to-users.*') || request()->routeIs('admin.activitylog') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Roles</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            {{-- <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.user-management.roles.*') ? 'active' : '' }}"
                                    href="{{ route('admin.user-management.roles.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Roles List</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item--> --}}

                            @if (auth()->user()->can('Role View'))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ request()->routeIs('admin.user-management.roles.*') ? 'active' : '' }}"
                                        href="{{ route('admin.user-management.roles.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Roles</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                            @endif

                            @if (auth()->user()->can('Assign Role To User View'))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ request()->routeIs('admin.role-management.assign-role-to-users.*') ? 'active' : '' }}"
                                        href="{{ route('admin.role-management.assign-role-to-users.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Assign Role
                                            To Users</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                            @endif

                            {{-- <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.user-management.permissions') ? 'active' : '' }}"
                                    href="{{ route('admin.user-management.permissions.index', 1) }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Permissions</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item--> --}}

                            {{--
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('admin.user-management.roles.show') ? 'active' : '' }}"
                                    href="{{ route('admin.user-management.roles.show', 1) }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">View Role</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item--> --}}
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->

                    @if (auth()->user()->can('Activity Log View'))
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('admin.activitylog') ? 'active' : '' }}"
                                href="{{ route('admin.activitylog') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Activity Logs</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif


                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif
    </div>
    <!--end::Menu-->
</div>
<!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
