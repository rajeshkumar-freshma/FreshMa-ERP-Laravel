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

            var table = $('#activity_log-table');
            console.log("table", table);

            $('.filter_button').on('click', function() {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var ip_address = $('#ip_address').val();
                var subject_type = $('#subject_type').val();
                var properties = $('#properties').val();

                table.on('preXhr.dt', function(e, settings, data) {
                    console.log("from_date:", fromDate);
                    console.log("to_date:", toDate);
                    console.log("ip_address:", ip_address);
                    console.log("subject_type:", subject_type);
                    console.log("properties:", properties);
                    console.log("data:", data);
                    // return {
                    //     from_date: fromDate,
                    //     to_date: toDate,
                    //     ip_address: ip_address,
                    //     subject_type: subject_type,
                    //     properties: properties,
                    // };
                    data.from_date = fromDate;
                    data.to_date = toDate;
                    data.ip_address = ip_address;
                    data.subject_type = subject_type;
                    data.properties = properties;
                });
                $('.filter_button').on('click', function() {
                    table.DataTable().ajax.reload();
                    return false;
                })

                // return false;
            });
        });
    </script>
@endsection
