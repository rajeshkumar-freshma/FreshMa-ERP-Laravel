<!--begin::Table-->
@include('pages.partials._datatable-toolbar-template', [
    'tableId' => 'loans-table',
    'dataTable' => $dataTable
])
<!--end::Table-->

<script>
    function orderStatusChange(loanId) {
        var selectedStatus = $('#loanStatusSelect').val();

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
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.loanStatusChanged') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    loan_id: loanId,
                    status: selectedStatus
                },
                success: function() {
                    window.location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    }
</script>
