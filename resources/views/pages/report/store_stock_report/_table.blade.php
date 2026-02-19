<x-default-layout>
    <div class="card table-responsive table-bordered">
        <!--begin::Card body-->
        {{-- <div class="card-body pt-6">
            <form action="{{ route('admin.store_stock_report') }}" method="GET" enctype="multipart/form-data">
                @csrf
                @method('get')
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
                                        @foreach ($branches as $key => $store)
                                            <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}"
                                                {{ $store->id == old('store_id') ? 'selected' : '' }}>
                                                {{ $store->store_name . ' - ' . $store->store_code }}</option>
                                        @endforeach
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
                                    name="from_date" value="{{ old('from_date') }}" />
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
                                    name="to_date" value="{{ old('to_date') }}" />
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
                                <a href="{{ route('admin.store_stock_report') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div> --}}
        <!--end::Card body-->

        <div class="card-body ">
            <table class="table align-middle table-row-dashed fs-6 gy-3">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th scope="col">products</th>
                        @foreach ($stores as $store)
                            <th scope="col">{{ $store->store_name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                    @foreach (@$products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            @foreach (@$stores as $store)
                                <td>
                                    @php
                                        $storeStock = $store->store_stock_update_inventory_details->firstWhere(
                                            'product_id',
                                            $product->id,
                                        );
                                    @endphp
                                    @if ($storeStock)
                                        {{ $storeStock->total_stock_sum }} <!-- Display total stock sum -->
                                    @else
                                        0
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach


                    {{-- @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            @foreach ($warehouses as $warehouse)
                                <td>
                                    @php
                                        $inventoryDetail = $product->store_stock_update
                                            ->where('store_id', $store->id)
                                            ->first();
                                    @endphp
                                    @if ($inventoryDetail)
                                        {{ $inventoryDetail->total_stock_sum }}
                                    @else
                                        0
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>

</x-default-layout>

{{-- Inject Scripts --}}
@section('scripts')
    {{-- {{ $dataTable->scripts() }} --}}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
@endsection
