<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Partnership Type',
            'menu_1_link' => route('admin.payment-type.index'),
            'menu_2' => 'Partnership Type',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.master.partnership_type._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</x-default-layout>
