<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Product Warehouse Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Product Warehouse Report',
        ])
    @endsection
    <div class="card-body">
        @include('pages.report.product_warehouse_report._table')
    </div>
</x-default-layout>
