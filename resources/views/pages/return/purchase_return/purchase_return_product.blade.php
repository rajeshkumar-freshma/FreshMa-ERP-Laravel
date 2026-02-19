<x-default-layout>
    <!--begin::Navbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.return.purchase_return.purchase_return_nav')
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
                        <span class="card-label fw-bold text-dark">Product Report Details</span>
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
                                <th class="text-end pe-0 min-w-150px">Return Quantity</th>
                                <th class="text-end pe-0 min-w-50px"> Per unit Price</th>
                                {{-- <th class="text-end pe-0 min-w-50px"> Tax</th>
                                <th class="text-end pe-0 min-w-25px">Dicount Amount</th> --}}
                                <th class="text-end pe-0 min-w-25px">Sub Total</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach (@$purchase_orders_return_details as $pruchase_return_datas)
                                <tr>
                                    <!--begin::Product ID-->
                                    <td class="text-end">{{ $pruchase_return_datas->name }}</td>
                                    <!--end::Product ID-->
                                    @if ($pruchase_return_datas->unit_id == 2)
                                        <td class="text-end">Gram</td>
                                    @else
                                        <td class="text-end">Kilo Gram</td>
                                    @endif
                                    <td class="text-end">{{ $pruchase_return_datas->quantity }}</td>
                                    <td class="text-end">{{ $pruchase_return_datas->per_unit_price }}</td>
                                    {{-- <td class="text-end">{{ $pruchase_return_datas->tax_value }}</td>
                                    <td class="text-end" data-order="58">{{ $pruchase_return_datas->discount_amount }} --}}
                                    </td>
                                    <td class="text-end" data-order="58">{{ $pruchase_return_datas->total }}</td>
                                    <!--end::Qty-->
                                </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($purchase_orders_return_details) && count($purchase_orders_return_details) > 0)
                        <div class="d-flex justify-content-between mx-0 row mt-1">
                            <div class="col-sm-12 col-md-6">
                                Showing {{ @$purchase_orders_return_details->firstItem() }} to
                                {{ @$purchase_orders_return_details->lastItem() }} of
                                {{ @$purchase_orders_return_details->total() }} entries
                            </div>
                            <div class="col-sm-12 col-md-6 float-right">
                                {{ @$purchase_orders_return_details->withQueryString()->links('vendor.pagination.custom') }}
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
