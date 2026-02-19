<!--begin::Action--->
@if (isset($unit_details))
    @if (count($product->product_wise_purchase_datas) > 0)
        @foreach ($product->product_wise_purchase_datas as $product_purchase_date)
            <p>{{ $product_purchase_date->total_unit . ' (' . $product_purchase_date->purchase_count . ')' }}</p>
        @endforeach
    @else
        <p>0</p>
    @endif
@endif
@if (isset($rate))
    @if (count($product->product_wise_purchase_datas) > 0)
        @foreach ($product->product_wise_purchase_datas as $product_purchase_date)
            <p>{{ $product_purchase_date->total_per_unit_price }}</p>
        @endforeach
    @else
        <p>0 (0)</p>
    @endif
@endif
@if (isset($amount))
    @if (count($product->product_wise_purchase_datas) > 0)
        @foreach ($product->product_wise_purchase_datas as $product_purchase_date)
            <p>{{ $product_purchase_date->total_amount }}</p>
        @endforeach
    @else
        <p>0</p>
    @endif
@endif
<!--end::Action--->
