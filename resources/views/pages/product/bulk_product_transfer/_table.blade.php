<!--begin::Table-->
@include('pages.partials._datatable-toolbar-template', [
    'tableId' => 'bulkproducttransfer-table',
    'dataTable' => $dataTable
])
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#bulkproducttransfer-table');
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
                        table.DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        // Handle error
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
