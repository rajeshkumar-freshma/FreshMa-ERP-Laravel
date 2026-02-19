@isset($loan_status)

    <select name="loanStatusSelect" id="loanStatusSelect" onchange="orderStatusChange({{ @$loan_id }})"
        class="form-select form-select-sm form-select-solid">
        @foreach (config('app.loan_status') as $item)
            <option class="badge bg" value="{{ $item['value'] }}"
                {{ $item['value'] == old('loan_status', $loan_status) ? 'selected' : '' }}>
                {{ $item['name'] }}</option>
        @endforeach
    </select>
@endisset
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
