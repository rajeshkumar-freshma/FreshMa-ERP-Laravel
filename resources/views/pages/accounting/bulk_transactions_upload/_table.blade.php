<!--begin::Table-->
<h2>Eaxmple Format</h2>
<b>Note:This Format of files only accecpted to import the satement and the column name also same don't change column name like-particulars</b>
<table class="table table-bordered report-table" id="report-table">
    <thead>
        <tr>
            <th>s.no</th>
            <th>date</th>
            <th>particulars</th>
            <th>debit</th>
            <th>credit</th>
            <th>balance</th>
        </tr>
    </thead>
    <tbody  id="transaction-table-body">
        <tr>
            <td>1</td>
            <td>12-12-2012</td>
            <td>Education Loan</td>
            <td>0.00</td>
            <td>100.00</td>
            <td>100</td>
        </tr>
    </tbody>
</table>
<!--end::Table-->

{{-- Inject Scripts --}}
<!-- Add or update the script section in your blade file -->
{{-- Inject Scripts --}}
@section('scripts')
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script>
        // Function to update the table content
        function updateTable(data) {
            var tableBody = $('#transaction-table-body');
            tableBody.empty(); // Clear existing content

            // Loop through the data and append rows to the table
            $.each(data, function (index, row) {
                var tableRow = '<tr>' +
                    '<td>' + row.sno + '</td>' +
                    '<td>' + row.date + '</td>' +
                    '<td>' + row.notes + '</td>' +
                    '<td>' + row.debit + '</td>' +
                    '<td>' + row.credit + '</td>' +
                    '<td>' + row.balance + '</td>' +
                    '</tr>';
                tableBody.append(tableRow);
            });
        }

        // Handle form submission and update the table
        $(document).ready(function () {
            $('#transactions-report-table').DataTable({
                // Your DataTable initialization options
            });

            $(".upload_button").click(function () {
                var bank_id = $('#bank_id').val();
                var fileInput = $('#file')[0];

                if (fileInput.files.length > 0) {
                    var file = fileInput.files[0];

                    // Use FormData to send the file to the server
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('bank_id', bank_id);

                    // Use AJAX to send the file to the server
                    $.ajax({
                        url: "{{ route('admin.bulk-transactions-upload') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Assuming the response contains the data you want to display
                            updateTable(response.data);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
            });
        });
    </script>
@endsection

