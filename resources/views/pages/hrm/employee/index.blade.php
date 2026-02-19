<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Employee',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Employee',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.hrm.employee._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
