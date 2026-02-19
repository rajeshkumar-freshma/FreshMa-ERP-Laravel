<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Product Fish Cutting Mapping',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Product Fish Cutting Mapping',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.product.fish_cutting_product_map._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
