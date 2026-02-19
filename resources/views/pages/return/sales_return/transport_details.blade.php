<x-default-layout>
    <!--begin::Navbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.return.sales_return.sales_return_nav')
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
                        <span class="card-label fw-bold text-dark">Transport Report Details</span>
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
                                <th class="text-end pe-0 min-w-100px">Transport Type</th>
                                <th class="text-end pe-0 min-w-100px">Transport Name</th>
                                <th class="text-end pe-0 min-w-150px">Transport Number</th>
                                <th class="text-end pe-0 min-w-150px">Departure Dates</th>
                                <th class="text-end pe-0 min-w-50px"> Arriving Date</th>
                                <th class="text-end pe-0 min-w-25px">From Location</th>
                                <th class="text-end pe-0 min-w-25px">To Location</th>
                                <th class="text-end pe-0 min-w-50px">Attachments</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach (@$sales_return_transport_trackings as $transport_tracking )
                            <tr>
                                <!--begin::Product ID-->
                                @if ($transport_tracking->transport_type_id == 1)
                                <td class="text-end">Bus</td>
                                @elseif ($transport_tracking->transport_type_id == 3)
                                <td class="text-end">Lorry</td>
                                @elseif ($transport_tracking->transport_type_id == 4)
                                <td class="text-end">Bike</td>
                                @elseif ($transport_tracking->transport_type_id == 5)
                                <td class="text-end">Tata Ace</td>
                                @else
                                <td class="text-end">-</td>
                                @endif

                                <td class="text-end">{{$transport_tracking->transport_name}}</td>
                                <!--begin::Date added-->
                                <td class="text-end">{{$transport_tracking->transport_number}}</td>
                                <!--end::Date added-->
                                <!--begin::Status-->
                                <td class="text-end">{{$transport_tracking->departure_datetime}}</td>
                                <td class="text-end">{{$transport_tracking->arriving_datetime}}</td>
                                <td class="text-end">{{$transport_tracking?->from_location ?? "-"}}</td>
                                <td class="text-end">{{$transport_tracking?->to_location ?? "-"}}</td>
                                <td class="text-end" data-order="58">{!! commoncomponent()->attachment_view($transport_tracking->file_path) !!}</td>
                                <!--end::Qty-->
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($sales_return_transport_trackings) && count($sales_return_transport_trackings) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ @$sales_return_transport_trackings->firstItem() }} to {{ @$sales_return_transport_trackings->lastItem() }} of
                            {{ @$sales_return_transport_trackings->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ @$sales_return_transport_trackings->withQueryString()->links('vendor.pagination.custom') }}
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
