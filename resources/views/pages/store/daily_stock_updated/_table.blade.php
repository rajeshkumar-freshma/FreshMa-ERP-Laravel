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

            var table = $('#store_stock_daily_updates-table');
            table.on('preXhr.dt', function(e, settings, data){
                data.product_id = $('#product_id').val();
                data.store_id = $('#store_id').val();
                data.date = $('#date').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            })
        });
    </script>
@endsection
