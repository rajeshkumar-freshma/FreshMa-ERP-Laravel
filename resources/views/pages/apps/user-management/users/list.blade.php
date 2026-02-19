<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Users List',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Users List',
        ])
    @endsection
    <div class="card">

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            {{ $dataTable->table() }}
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @section('scripts')
        {{ $dataTable->scripts() }}
    @endsection

</x-default-layout>
