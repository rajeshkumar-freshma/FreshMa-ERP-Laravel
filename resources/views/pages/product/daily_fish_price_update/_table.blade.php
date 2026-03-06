<!--begin::Table-->
@include('pages.partials._datatable-toolbar-template', [
    'tableId' => 'dailyfishpriceupdate-table',
    'dataTable' => $dataTable
])
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".fsh_flat_datepicker").flatpickr();

            var table = $('#dailyfishpriceupdate-table');
            table.on('preXhr.dt', function(e, settings, data){
                data.product_id = $('#product_id').val();
                data.store_id = $('#store_id').val();
                data.date = $('#date').val();
            });
        });
    </script>
@endsection
