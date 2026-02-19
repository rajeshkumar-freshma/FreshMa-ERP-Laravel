<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Assign Role to Users',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Assign Role to Users',
        ])
    @endsection
    <!--begin::Card header-->
    @include('pages.partials.form_header', ['header_name' => 'Role'])
    <!--begin::Card header-->
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.roles._table')
        </div>
    </div>
</x-default-layout>
