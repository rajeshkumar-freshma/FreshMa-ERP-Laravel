<!--begin::Table-->
@include('pages.partials._datatable-toolbar-template', [
    'tableId' => 'vendorsale-table',
    'dataTable' => $dataTable
])
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
@endsection
