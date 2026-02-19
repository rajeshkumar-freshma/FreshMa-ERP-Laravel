<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Daily Store Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Daily Store Report',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form id="filterForm" action="{{ route('admin.dailystorereportdata') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="store_id" class="form-label">{{ __('Store') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="store_id" id="store_id" aria-label="{{ __('Select Branch') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Branch..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Branch..') }}</option>
                                        @if (isset($stores))
                                            @foreach ($stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}"
                                                    {{ $store->id == old('store_id', @$store_id) ? 'selected' : '' }}>
                                                    {{ $store->store_name . ' - ' . $store->store_code }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="from_date" value="{{ old('from_date', @$from_date) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_date" class=" form-label">{{ __('To Date') }}</label>
                            <div class="input-group">
                                <input id="to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="to_date" value="{{ old('to_date', @$to_date) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="submit"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.dailystorereportdata') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->can('Daily Store Report Download'))
                        <div class="col-md-4">
                            <div class="mb-5">
                                <div class="d-flex justify-content-right py-6">
                                    <button type="button" class="btn btn-sm btn-success me-2 print-btn"
                                        onclick="window.print()">{{ __('Print') }}</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
            @include('pages.partials.custom_pagination_dropdown')
            <div class="table-responsive">
                <table class="table table-bordered report-table" id="report-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product List</th>
                            <th>From Warehouse</th>
                            <th>Store Stock List</th>
                            <th>Cutting fish</th>
                            <th>From Another Store</th>
                            <th>Total</th>
                            <th>Weight (kg)</th>
                            <th>Avg Sales Price</th>
                            <th>Total Sale Price</th>
                            <th>Cutting fish weight</th>
                            <th>wastage</th>
                            <th>Spoilage fish</th>
                            <th>Spoilage fish egg</th>
                            <th>Transfer to Another Store</th>
                            <th>Excess Qty </th>
                            <th>Physical Stock</th>
                        </tr>

                    </thead>
                    <tbody>
                        @php
                            $totalSalesOrder = 0;
                            $total = 0;
                            $cuttingFish = 0;
                            $stockTotal = 0;
                            $physiclStockTostla = 0;
                        @endphp
@if (isset($products) && count($products) > 0)
@foreach ($products as $key => $product)
    @php
        // Calculate cutting fish total
        $cuttingFish = $product->slice + $product->head + $product->tail;

        // Calculate total stock
        $total = $product->initial_stock +
                 $product->transfer_quantity_in +
                 $cuttingFish +
                 $product->transfer_quantity_from_another_store;

        // Final stock and physical stock values
        $stockTotal = $product->final_stock;
        $physicalStockTotal = $product->closing_stock;

        // Determine the row class based on stock comparison
        $rowClass = $stockTotal < $physicalStockTotal
                    ? 'bg-danger'
                    : ($stockTotal > $physicalStockTotal
                       ? 'bg-success'
                       : 'bg-primary');
    @endphp

                                <tr class="{{ $rowClass }}">
                                    <td>{{ $products->firstItem() + $key }}</td>
                                    <td>
                                        {{ $product->name }}
                                    </td>
                                    <td>{{ number_format($product->transfer_quantity_in, 3) }}</td>
                                    <td>{{ number_format($product->initial_stock, 3) }}</td>
                                    <td>{{ number_format($cuttingFish, 3) }}</td>
                                    <td>{{ number_format($product->transfer_quantity_from_another_store, 3) }}</td>
                                    <td>{{ $total }}</td>
                                    <td>{{ number_format($product->sales_order_quantity, 3) }}</td>
                                    <td>{{ $product->sales_order_quantity != 0 ? number_format($product->sales_order_sub_total / $product->sales_order_quantity, 2) : 0 }}
                                    </td>
                                    <td>{{ number_format($product->sales_order_sub_total, 2) }}</td>
                                    {{-- @php
                                    $totalSalesOrder = $product->sales_order_quantity + $product->sales_order_sub_total;
                                @endphp
                                <td>{{ $totalSalesOrder }}</td> --}}
                                    <td>{{ number_format($product->cutting_fish_weight, 3) }}</td>
                                    <td>{{ number_format($product->total_wastage, 3) }}</td>
                                    <td>{{ number_format($product->spoilage_product_quantity, 3) }}</td>
                                    <td>{{ number_format($product->spoilage_egg_quantity, 3) }}</td>
                                    <td>{{ number_format($product->transfer_quantity_out, 3) }}</td>
                                    <td>{{ number_format($product->final_stock, 3) }}</td>
                                    <td>{{ number_format($product->closing_stock, 3) }}</td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="15" class="text-center">No data available in table</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (isset($products) && count($products) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                            {{ $products->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ $products->withQueryString()->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!--end::Card body-->
    </div>
    @section('scripts')
        @include('pages.partials.custom_pagination_script')
        <script>
            // $(".fsh_flat_datepicker").flatpickr();
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr(".fsh_flat_datepicker", {
                    dateFormat: "Y-m-d", // Set the desired date format
                    // defaultDate: new Date() // Set the current date as the default value
                });
            });
        </script>
    @endsection
    <!--end::Card-->
</x-default-layout>
