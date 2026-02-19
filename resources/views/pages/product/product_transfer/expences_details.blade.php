<x-default-layout>
    <!--begin::Navbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.product.product_transfer.transfernav')
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
                        <span class="card-label fw-bold text-dark">Expences Report Details</span>
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
                                <th class="text-end pe-0 min-w-100px">Expense Type</th>
                                <th class="text-end pe-0 min-w-100px">Expense Amount</th>
                                <th class="text-end pe-0 min-w-100px">Is billable</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($product_expences as $product_expence)
                            <tr>
                                <!--begin::Product ID-->
                                <td class="text-end">{{ $product_expence->type_name }}</td>

                                <td class="text-end">{{ $product_expence->ie_amount }}</td>
                                @if ($product_expence->is_billable)
                                <td class="text-end">
                                    <span class="badge bg-success text-white">
                                        Yes
                                    </span>
                                </td>
                                @else
                                <td class="text-end">
                                    <span class="badge bg-danger text-white">
                                        No
                                    </span>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($product_expences) && count($product_expences) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ $product_expences->firstItem() }} to {{ $product_expences->lastItem() }} of
                            {{ $product_expences->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ $product_expences->withQueryString()->links('vendor.pagination.custom') }}
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
