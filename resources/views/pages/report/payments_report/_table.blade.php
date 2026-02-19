<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".fsh_flat_datepicker").flatpickr();
            var table = $('#payment-transactions-table');

            table.on('preXhr.dt', function(e, settings, data) {
                data.transaction_type = $('#transaction_type').val();
                data.type = $('#type').val();
                data.from_date = $('#from_date').val(); // Correct property name
                data.to_date = $('#to_date').val(); // Correct property name
                data.payment_type = $('#payment_type_id').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            });
        });
    </script>
@endsection
