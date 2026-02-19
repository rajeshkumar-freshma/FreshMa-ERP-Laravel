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

            var table = $('#salescredits-table');
            table.on('preXhr.dt', function(e, settings, data){
                data.bill_no = $('#bill_no').val();
                data.store_id = $('#store_id').val();
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            })
        });
    </script>
@endsection
