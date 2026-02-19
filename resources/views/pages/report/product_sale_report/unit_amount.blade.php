<!--begin::Action--->
@if (isset($unit_details))
    @if (count($product->product_sale_datas) > 0)
        @foreach ($product->product_sale_datas as $product_sale_data)
            <p>{{ $product_sale_data->total_unit . ' (' . $product_sale_data->sale_count . ')' }}</p>
        @endforeach
    @else
        <p>0</p>
    @endif
@endif
@if (isset($rate))
    @if (count($product->product_sale_datas) > 0)
        @foreach ($product->product_sale_datas as $product_sale_data)
            <p>{{ $product_sale_data->per_unit_price }}</p>
        @endforeach
    @else
        <p>0 (0)</p>
    @endif
@endif
@if (isset($amount))
    @if (count($product->product_sale_datas) > 0)
        @foreach ($product->product_sale_datas as $product_sale_data)
            <p>{{ $product_sale_data->total_amount }}</p>
        @endforeach
    @else
        <p>0</p>
    @endif
@endif
<!--end::Action--->
