<x-default-layout>
    <!--begin::Navbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.sales.sales_order.sales_nav')
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
                        <span class="card-label fw-bold text-dark">Product Details Report</span>
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
                                <th class="text-end pe-0 min-w-100px">Product Name</th>
                                <th class="text-end pe-0 min-w-100px">Unit</th>
                                <th class="text-end pe-0 min-w-150px">Request Quantity</th>
                                <th class="text-end pe-0 min-w-150px">Given Quantity</th>
                                <th class="text-end pe-0 min-w-50px"> Amount</th>
                                <th class="text-end pe-0 min-w-50px"> Tax</th>
                                <th class="text-end pe-0 min-w-25px">Discount Type</th>
                                <th class="text-end pe-0 min-w-25px">Amount/Percentage</th>
                                <th class="text-end pe-0 min-w-25px">Sub Total</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($sales_details as $sales_detail )
                            <tr>
                                <!--begin::Product ID-->
                                <td class="text-end">{{$sales_detail->name}}</td>
                                <!--end::Product ID-->
                                @if ($sales_detail->unit_id == 2)
                                <td class="text-end">Gram</td>
                                @else<td class="text-end">Kilo Gram</td>
                                @endif

                                <td class="text-end">{{$sales_detail->request_quantity}}</td>
                                <!--begin::Date added-->
                                <td class="text-end">{{$sales_detail->given_quantity}}</td>
                                <!--end::Date added-->
                                <!--begin::Status-->
                                <td class="text-end">{{$sales_detail->amount}}</td>
                                <td class="text-end">{{$sales_detail->tax_value}}</td>
                                @if ($sales_detail->discount_type == 1)
                                <td class="text-end" data-order="58">Fixed</td>
                                @else
                                <td class="text-end" data-order="58">Percentage</td>
                                @endif

                                <td class="text-end" data-order="58">{{$sales_detail->discount_amount}}</td>
                                <td class="text-end" data-order="58">{{$sales_detail->sub_total}}</td>
                                <!--end::Qty-->
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($sales_details) && count($sales_details) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ $sales_details->firstItem() }} to {{ $sales_details->lastItem() }} of
                            {{ $sales_details->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ $sales_details->withQueryString()->links('vendor.pagination.custom') }}
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
