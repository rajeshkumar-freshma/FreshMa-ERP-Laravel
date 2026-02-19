<!--begin::Table-->
{{ $dataTable->table(['class' => 'table table-bordered table-hover', 'id' => 'fishcuttingdetailsreport-table']) }}

<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".fsh_flat_datepicker").flatpickr();
            var table = $('#fishcuttingdetailsreport-table');

            table.on('preXhr.dt', function(e, settings, data) {
                // Add form data to the AJAX request
                data.product_id = $('#product_id').val();
                data.store_id = $('#store_id').val();
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            });


            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            });
        });
    </script>
@endsection
