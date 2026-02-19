<x-default-layout>
    <!--begin::Card-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Table Widget 5-->
        <div class="card card-flush h-xl-100">
            <div class="card-body">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-3">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center pe-0 min-w-50px">Store Name</th>
                            <th class="text-center pe-0 min-w-50px">Date</th>
                            <th class="text-center pe-0 min-w-50px">Sales Count</th>
                            <th class="text-center pe-0 min-w-50px">Total Amount</th>
                            <th class="text-center pe-0 min-w-50px">View Orders</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($salesOrder as $item)
                            <tr>
                                <td class="text-center">{{ $item->store->store_name ?? '' }}</td>
                                <td class="text-center">{{ $item->delivered_date }}</td>
                                <td class="text-center">{{ $item->total_count }}</td>
                                <td class="text-center">{{ $item->total_amount }}</td>
                                <td class="no-wrap d-flex">
                                    <div class="btn-group">
                                        {{-- <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button> --}}
                                        <form action="{{ route('admin.dailysalesordersgetdate') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="store_id" value="{{ $item->store_id }}">
                                            <input type="hidden" name="delivered_date"
                                                value="{{ $item->delivered_date }}">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- @if (isset($item) && count($item) > 0)
                        <div class="d-flex justify-content-between mx-0 row mt-1">
                            <div class="col-sm-12 col-md-6">
                                Showing {{ $item->firstItem() }} to {{ $item->lastItem() }} of
                                {{ $item->total() }} entries
                            </div>
                            <div class="col-sm-12 col-md-6 float-right">
                                {{ $salesOrder->withQueryString()->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    @endif --}}
                    <!--end::Table body-->
                </table>
                <!--end::Table-->

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Table Widget 5-->

    </div>
    {{-- <div class="card">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Store Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Sales Count</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">View Orders</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salesOrder as $item)
                    <tr>
                        <td>{{ $item->store->store_name ?? '' }}</td>
                        <td>{{ $item->delivered_date }}</td>
                        <td>{{ $item->total_count }}</td>
                        <td>{{ $item->total_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
</x-default-layout>
