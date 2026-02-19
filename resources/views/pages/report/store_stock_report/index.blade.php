<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Store Stack Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Store Stack Report',
        ])
    @endsection
    <div class="card-body">
        @include('pages.report.store_stock_report._table')
    </div>
</x-default-layout>
