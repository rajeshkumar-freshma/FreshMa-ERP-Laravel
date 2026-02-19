<!--begin::Table-->
{{-- <table class="table table-bordered report-table" id="report-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Particulars</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table> --}}
<!--end::Table-->

{{-- Inject Scripts --}}
{{-- @section('scripts')
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script>
        $(".fsh_flat_datepicker").flatpickr();

        dataTable();

        function dataTable() {
            var bank_id = $('#bank_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            table = $('#report-table').DataTable({
                dom: '<"datatable-header d-flex"l><"datatable-scroll-wrap"t><"datatable-footer d-flex justify-content-between"ip>',
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                "bDestroy": true,
                buttons: [
                    'excel', 'csv', 'pdf', 'print', 'reset', 'reload',
                ],
                ajax: {
                    url: "{{ route('admin.bank-transactions-report') }}",
                    type: 'GET',
                    data: {
                        bank_id: bank_id,
                        from_date: from_date,
                        to_date: to_date,
                    },
                },
                "columns": [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'transaction_date',
                        name: 'Date'
                    },
                    {
                        data: 'notes',
                        name: 'Particulars'
                    },
                    {
                        data: 'debit', // Use the new column for Debit
                        name: 'Debit'
                    },
                    {
                        data: 'credit', // Use the new column for Credit
                        name: 'Credit'
                    },
                    {
                        data: 'available_balance',
                        name: 'Balance'
                    },
                ],
            });
        }

        $(".filter_button").click(function() {
            dataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#transactions-report-table').DataTable({
                // Your DataTable initialization options
            });
        });
    </script>

@endsection --}}

<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    {{-- <script>
        $(document).ready(function() {
            $(".fsh_flat_datepicker").flatpickr();
            var table = $('#usersassingedrole-table');

            table.on('preXhr.dt', function(e, settings, data) {
                data.transaction_account_id = $('#bank_id').val();
                data.transaction_date = $('#from_date').val();
                data.transaction_date = $('#to_date').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            });
        });
    </script> --}}
@endsection
