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
                        <span class="card-label fw-bold text-dark">Product Purchase Order Details Report</span>
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
                                <th class="text-end pe-0 min-w-100px">Purchase Order Number</th>
                                <th class="text-end pe-0 min-w-100px">Supplier</th>
                                <th class="text-end pe-0 min-w-150px">Request Quantity</th>
                                <th class="text-end pe-0 min-w-50px"> Given Quantity</th>
                                <th class="text-end pe-0 min-w-50px"> Delivery Date</th>
                                <th class="text-end pe-0 min-w-50px"> Created Date</th>
                                <th class="text-end pe-0 min-w-25px">Price</th>
                                <th class="text-end pe-0 min-w-25px">Action</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($purchase_tables as $purchse)
                            <tr>
                                <!--begin::Product ID-->
                                <td class="text-end">{{$purchse->purchase_order_number}}</td>
                                <!--end::Product ID-->
                                <td class="text-end">{{$purchse->supplier_name}}</td>
                                <!--begin::Date added-->
                                <td class="text-end">{{$purchse->request_quantity}}</td>
                                <td class="text-end">{{$purchse->given_quantity}}</td>
                                <!--end::Date added-->
                                <!--begin::Status-->
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 badge-light-primary">{{$purchse->delivery_date}}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 badge-light-primary">{{$purchse->created_at}}</span>
                                </td>
                                <!--end::Status-->
                                <!--begin::Qty-->
                                <td class="text-end" data-order="58">
                                    <span class="text-dark fw-bold">{{$purchse->amount}}</span>
                                </td>
                                <td class="text-end" data-order="58">
                                    <span><a href="{{ route('admin.purchase-order.index') }}"><i class='fas fa-eye' style='font-size:15px;color:green'></i></a></span>
                                </td>
                                <!--end::Qty-->
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($purchase_tables) && count($purchase_tables) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ $purchase_tables->firstItem() }} to {{ $purchase_tables->lastItem() }} of
                            {{ $purchase_tables->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ $purchase_tables->withQueryString()->links('vendor.pagination.custom') }}
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
</x-default-layout>
