<x-default-layout>
    <!--begin::Card header-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Warehouse',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Warehouse',
        ])
    @endsection
    <!--begin::Card header-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.master.warehouse._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
