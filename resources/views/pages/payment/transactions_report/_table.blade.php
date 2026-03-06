<!--begin::Table-->
@include('pages.partials._datatable-toolbar-template', [
    'tableId' => 'payment_transactions-table',
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
            var table = $('#payment_transactions-table');

            table.on('preXhr.dt', function(e, settings, data) {
                data.from_date = $('#from_date').val() || $('#payment_transactions-table-date-from').val();
                data.to_date = $('#to_date').val() || $('#payment_transactions-table-date-to').val();
                data.transaction_type = $('#transaction_type').val();
                data.type = $('#type').val();
                data.payment_type_id = $('#payment_type_id').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            });
        });
    </script>
@endsection
