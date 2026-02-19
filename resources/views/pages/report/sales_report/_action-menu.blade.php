<!--begin::Action--->
@if (isset($imageData))
    <td>
        @if ($imageData!=null)
            <img src="{{ $imageData }}" style="width: 50px; height : 50px;">
        @else
            -
        @endif
    </td>
@endif
@if (isset($datas))
    <td class="text-end">
        <td class="text-end d-flex">
            <div class="d-flex">
                <a href="{{ route('admin.sales-order.index').$query_string }}">
                    <button class="btn btn-sm btn-light btn-active-light-primary">
                        <i class="fa fa-eye"></i>
                    </button>
                </a>
            </div>
        </td>
        <!--end::Dropdown wrapper-->
        {{-- <div class="menu menu-column menu-gray-600 menu-active-primary menu-hover-light-primary menu-here-light-primary menu-show-light-primary fw-semibold" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item here" data-kt-menu-trigger="click" data-kt-menu-placement="right-start">
                <!--begin::Menu link-->
                <a class="menu-link btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary justify-content-center">
                    <span class="menu-title">...</span>
                </a>
                <!--end::Menu link-->

                <!--begin::Menu sub-->
                <div class="menu-sub menu-sub-dropdown w-175px py-2">
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('admin.machine-sales.edit', $datas['id']) }}" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </span>
                            <span class="menu-title">View Bill</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::Menu sub-->
            </div>
            <!--end::Menu item-->
        </div> --}}
    </td>
@endif
<!--end::Action--->
