<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script>
        function orderStatusChange(loanId) {
            var selectedStatus = $('#loanStatusSelect').val();
            console.log("selectedStatus");
            console.log(selectedStatus);
            console.log("loanId");
            console.log(loanId);

            Swal.fire({
                title: `Are you sure to change?`,
                text: "If you change this, it will be gone forever.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ms-1'
                },
                buttonsStyling: false
            }).then(function(result) {
                // Make an AJAX request to the statusChanged function in LoanController
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.loanStatusChanged') }}", // Adjust the URL based on your route
                    data: {
                        _token: '{{ csrf_token() }}',
                        loan_id: loanId, // Pass the loanId here
                        status: selectedStatus
                    },
                    success: function(response) {
                        console.log("response");
                        console.log(response);
                        window.location.reload();
                        // Optionally, you can handle the response or perform other actions
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            });
        }
    </script>

    {{-- <script>
        $(function() {
            $('body').on('click', '.statuschange', function() {
                var warehouse_id = $(this).attr('data-warehouse_id');
                var status_value = this.checked ? 1 : 0;
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.warehouse.statuschange') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'warehouse_id': warehouse_id,
                        'status_value': status_value,
                    },
                    success: (function(result) {
                        alertMessage(result.message, result.status, 'status')
                        $('#success').css("display", "block");
                        $("#success").delay(1500).fadeOut(300);
                    })
                });
            })

            $('body').on('click', '.defaultwarehouse', function() {
                var warehouse_id = $(this).attr('data-warehouse_id');
                var value = this.checked ? 1 : 0;
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.warehouse.defaultwarehouse.update') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'value': value,
                        'warehouse_id': warehouse_id,
                        'default': 1,
                    },
                    success: (function(result) {
                        alertMessage(result.message, result.status, 'default')
                        $('#success').css("display", "block");
                        $("#success").delay(1500).fadeOut(300);
                    })
                });
            });
        })


        function alertMessage(message, status, type) {
            if (status == 200) {
                var titleData = "Success";
                var display_icon = "success"; // 'success' is the correct value for the success icon
            } else {
                var titleData = "Oops!";
                var display_icon = "error"; // 'error' is the correct value for the error icon
            }
            Swal.fire({
                title: titleData,
                text: message,
                icon: display_icon, // Use the variable here without quotes
                showCancelButton: false,
                confirmButtonText: 'Okay!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ms-1'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    var table = $('#warehouse-table');
                    table.DataTable().ajax.reload();
                    return false;
                }
            });
        }
    </script>  --}}
@endsection
