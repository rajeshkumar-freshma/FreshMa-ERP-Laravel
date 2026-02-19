@include('pages.partials.product_search.product_search', ['supplier_id' => @$supplier])
<!--End::Product Search-->

<!--begin::Product Items-->
@if (isset($is_view_page))
    @include('pages.partials.product_search.item_view', ['data' => $loop_data, 'is_old_data' => false])
    @include('pages.partials.product_search.calculation_part_view', ['calculation_data' => @$main_data])
@else
    @if (old('products.product_id'))
        @include('pages.partials.product_search.item', ['data' => $loop_data, 'is_old_data' => true])
    @else
        @include('pages.partials.product_search.item', ['data' => $loop_data, 'is_old_data' => false])
    @endif
    @include('pages.partials.product_search.calculation_part', ['calculation_data' => @$main_data])
@endif

