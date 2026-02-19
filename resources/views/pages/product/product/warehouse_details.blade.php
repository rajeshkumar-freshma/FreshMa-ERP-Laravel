<x-default-layout>
    <!--begin::Navbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.product.product.product_details')
        </div>
        <!--end::Navbar-->
        <!--begin::Col-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Table Widget 5-->
            <div class="card card-flush h-xl-100">
                <!--begin::Card header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark"> Warehouse Stock Report</span>
                    </h3>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-end pe-0 min-w-100px">Warehouse Name</th>
                                <th class="text-end pe-0 min-w-150px">Warehouse Stock</th>
                                <th class="text-end pe-0 min-w-150px">Updated Date</th>
                                <th class="text-end pe-0 min-w-25px">Action</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($warehouse_details as $warehouse_detail)
                            <tr>
                                <!--begin::Product ID-->
                                <td class="text-end">{{$warehouse_detail->warehouse_name}}</td>
                                <!--end::Product ID-->
                                <td class="text-end">{{$warehouse_detail->weight}}</td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 badge-light-primary">{{$warehouse_detail->updated_at}}</span>
                                </td>
                                <td class="text-end" data-order="58">
                                    <span><a href="{{ route('admin.warehouse_stock_report') }}"><i class='fas fa-eye' style='font-size:15px;color:green'></i></a></span>
                                </td>
                                <!--end::Qty-->
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($warehouse_details) && count($warehouse_details) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ $warehouse_details->firstItem() }} to {{ $warehouse_details->lastItem() }} of
                            {{ $warehouse_details->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ $warehouse_details->withQueryString()->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    @endif
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Table Widget 5-->
        </div>
        <!--end::Col-->
    </div>
</x-default-layout>F
