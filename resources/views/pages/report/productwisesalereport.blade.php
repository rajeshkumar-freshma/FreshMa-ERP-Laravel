<x-default-layout>
    @section('_toolbar')
        @php
            $data['title'] = 'Product wise Sales Report';
            $data['menu_1'] = 'Product wise Sales Report';
            $data['menu_1_link'] = route('admin.productwisesalereport');
            $data['menu_2'] = '';
        @endphp
        @include(config('settings.KT_THEME_LAYOUT_DIR') . '/partials/sidebar-layout/_toolbar', ['data' => $data])
    @endsection

    <!--begin::Basic info-->
    <div class="card">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Product Name</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $key }}</td>
                        <td>{{ $product->name }}</td>
                        @if (count($product->product_sale_datas))
                            <td>
                                @foreach ($product->product_sale_datas as $product_sale_data)
                                    <p>{{ $product_sale_data->total_unit }}</p>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($product->product_sale_datas as $product_sale_data)
                                    <p>{{ $product_sale_data->per_unit_price . ' (' . $product_sale_data->sale_count . ')' }}</p>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($product->product_sale_datas as $product_sale_data)
                                    <p>{{ $product_sale_data->total_amount }}</p>
                                @endforeach
                            </td>
                        @else
                            <td>0</td>
                            <td>0 (0)</td>
                            <td>0</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-between mx-0 row mt-1">
            <div class="col-sm-12 col-md-6">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                {{ $products->total() }} entries
            </div>
            <div class="col-sm-12 col-md-6 float-right">
                {{ $products->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    <!--end::Basic info-->
</x-default-layout>
