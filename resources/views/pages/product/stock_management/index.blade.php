<x-default-layout>
    <!--begin::Card header-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Stock Management',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Stock Management',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.product.stock_management._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
