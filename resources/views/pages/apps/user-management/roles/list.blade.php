<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Roles',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Roles',
        ])
    @endsection
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <livewire:permission.role-list></livewire:permission.role-list>
    </div>
    <!--end::Content container-->

    <!--begin::Modal-->
    <livewire:permission.role-modal></livewire:permission.role-modal>
    <!--end::Modal-->

</x-default-layout>
