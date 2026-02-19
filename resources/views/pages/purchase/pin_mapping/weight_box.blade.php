<!--begin::Action--->
@if (isset($unit_details))
    @if (count($product->product_sale_datas) > 0)
        @foreach ($product->product_pin_mapping_histories as $product_sale_data)
            <p>{{ '[' . $product_sale_data->box_no . ']' . ' -' . $product_sale_data->quantity }}</p>
        @endforeach
    @else
        <p>0</p>
    @endif
@endif

@if (isset($date))
    @if (count($date->product_sale_datas) > 0)
        @foreach ($date->product_pin_mapping_histories as $product_sale_data)
            <p>{{ $product_sale_data->date }}</p>
        @endforeach
    @endif
@endif
@if (isset($type))
    @foreach ($type->product_pin_mapping_histories as $product_sale_data)
        @foreach (config('app.product_box_mapping') as $types)
            @if ($types['value'] == $product_sale_data->type)
                <p class="badge badge-light-{{ $types['color'] }}">{{ $types['name'] }}</p>
            @endif
        @endforeach
    @endforeach
@endif
<!--end::Action--->
