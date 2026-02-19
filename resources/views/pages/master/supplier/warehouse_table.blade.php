<x-default-layout>
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Warehouse Indent request</h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Navbar-->

                @include('pages.master.supplier.user_details')
                <!--end::Navbar-->
                <!--begin::details View-->
                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Warehouse Indent Details</h3>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Action-->
                        {{-- <a href="{{ route('admin.supplier.edit', $users->id) }}"
                            class="btn btn-sm btn-primary align-self-center" target="_blank">Edit Profile</a> --}}
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-end pe-0 min-w-100px">Request Code</th>
                                    <th class="text-end pe-0 min-w-150px">Request Date</th>
                                    <th class="text-end pe-0 min-w-50px">Expected Date</th>
                                    <th class="text-end pe-0 min-w-50px"> Request Quentity</th>
                                    <th class="text-end pe-0 min-w-25px">Price</th>
                                    <th class="text-end pe-0 min-w-25px">Action</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @foreach ($warehouse as $warehouses)
                                    <tr>
                                        <!--begin::Product ID-->
                                        <td class="text-end">{{ $warehouses->request_code }}</td>
                                        <!--end::Product ID-->
                                        <!--begin::Date added-->
                                        <td class="text-end">{{ $warehouses->request_date }}</td>
                                        <!--end::Date added-->
                                        <!--begin::Price-->
                                        <td class="text-end">{{ $warehouses->expected_date }}</td>
                                        <!--end::Price-->
                                        <!--begin::Status-->
                                        <td class="text-end">
                                            <span
                                                class="badge py-3 px-4 fs-7 badge-light-primary">{{ $warehouses->total_request_quantity }}</span>
                                        </td>
                                        <!--end::Status-->
                                        <!--begin::Qty-->
                                        <td class="text-end" data-order="58">
                                            <span class="text-dark fw-bold">{{ $warehouses->total_amount }}</span>
                                        </td>
                                        <td class="text-end" data-order="58">
                                            <span><a href="{{ route('admin.warehouse-indent-request.index') }}"><i
                                                        class='fas fa-eye'
                                                        style='font-size:15px;color:green'></i></a></span>
                                        </td>
                                        <!--end::Qty-->
                                    </tr>
                                @endforeach

                            </tbody>
                            @if (isset($warehouse) && count($warehouse) > 0)
                                <div class="d-flex justify-content-between mx-0 row mt-1">
                                    <div class="col-sm-12 col-md-6">
                                        Showing {{ $warehouse->firstItem() }} to {{ $warehouse->lastItem() }} of
                                        {{ $warehouse->total() }} entries
                                    </div>
                                    <div class="col-sm-12 col-md-6 float-right">
                                        {{ $warehouse->withQueryString()->links('vendor.pagination.custom') }}
                                    </div>
                                </div>
                            @endif
                            <!--end::Table body-->
                        </table>
                        <!--end::Card body-->
                    </div>
                    <!--end::details View-->
                    @include('pages.partials.form_footer', [
                        'is_save' => false,
                        'back_url' => route('admin.supplier.index'),
                    ])
                </div>
                <!--end::Content container-->

            </div>
            <!--end::Content-->
        </div>
    </div>

    <!--end::Content wrapper-->
    <!--begin::Footer-->
</x-default-layout>
