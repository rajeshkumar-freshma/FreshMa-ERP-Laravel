<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#producttransfer-table');
            table.on('blur', '.editable', function() {
                console.log('test');
                var id = $(this).data('id');
                var value = $(this).text();

                // Send an AJAX request to update the value in the database
                $.ajax({
                    url: "{{ route('admin.bulk-product-transfer.store') }}",
                    method: "POST",
                    data: {
                        id: id,
                        name: value,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Handle success
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        // Handle error
                        console.log(xhr.responseText);
                    }
                });
                // data.bill_no = $('#bill_no').val();
                // data.machine_id = $('#machine_id').val();
                // data.from_date = $('#from_date').val();
                // data.to_date = $('#to_date').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            });
        });
    </script>
@endsection
