<!--begin::Table-->
{{ $dataTable->table(['class' => 'table table-bordered table-hover', 'id' => 'suppliers-table']) }}

<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".fsh_flat_datepicker").flatpickr();
            var table = $('#suppliers-table');

            table.on('preXhr.dt', function(e, settings, data) {
                // Add form data to the AJAX request
                data.transaction_from_date = $('#transaction_from_date').val();
                data.transaction_to_date = $('#transaction_to_date').val();
                data.purchase_from_date = $('#purchase_from_date').val();
                data.purchase_to_date = $('#purchase_to_date').val();
                data.purchase_number = $('#purchase_number').val();
                data.supplier_id = $('#supplier_id').val();
            });


            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            });
        });
    </script>
@endsection
