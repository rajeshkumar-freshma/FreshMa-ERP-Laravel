<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Denomination',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Denomination',
        ])
    @endsection
    <!--begin::Basic info-->
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.master.denomination._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
