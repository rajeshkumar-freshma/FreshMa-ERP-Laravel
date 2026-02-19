<!--begin::Table-->
<table class="table table-bordered report-table" id="report-table">
    <thead>
        <tr>
            <th>No</th>
            {{-- <th>Branch</th> --}}
            <th>Machine Name</th>
            <th>Order Count</th>
            <th>Total Order Amount</th>
            <th width="100px">Action</th>
        </tr>
    </thead>
    <tbody>
        <td></td>
    </tbody>
</table>

<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
{{-- {{ $dataTable->scripts() }} --}}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script>
        $(".fsh_flat_datepicker").flatpickr();

        var table = $('#report-table').DataTable({
            dom: '<"datatable-header d-flex"l><"datatable-scroll-wrap"t><"datatable-footer d-flex justify-content-between"ip>',
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            "bDestroy": true,
            ajax: {
                url: "{{ route('admin.branch-machine-sale.index') }}",
                type: 'GET',
                data: function(d) {
                    d.store_id = $('#store_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.machine_id = $('#machine_id').val();
                    d.warehouse_id = $('#warehouse_id').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                // {
                //     data: 'store_name',
                //     name: 'Branch'
                // },
                {
                    data: 'machine_name',
                    name: 'Machine'
                },
                {
                    data: 'order_count',
                    name: 'Order Count'
                },
                {
                    data: 'total_order_amount',
                    name: 'Total Order Amount'
                },
                {
                    data: 'action',
                    orderable: false
                },
            ]
        });

        $(".filter_button").click(function() {
            table.ajax.reload();
        });
    </script>
@endsection
