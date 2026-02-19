@section('scripts')
    @include('pages.partials.date_picker')
    <script>
        $(document).ready(function() {
            getBankBalance();

            $(document).on('change', '#transaction_account', function() {
                getBankBalance();
            });

            function getBankBalance() {
                var selectedOption = $("#transaction_account option:selected");
                var bankId = selectedOption.val();
                console.log("bankId");
                console.log(bankId);

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.get_bank_balance') }}", // Removed extra 'url:' typo
                    data: {
                        bankId: bankId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status === 200) {
                            var bankBalance = data.bank_balance;
                            $("#available_balance").val(bankBalance);
                        } else {
                            // Handle other cases if needed
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error scenarios if needed
                    }
                });
            }
        });
        $(document).ready(function() {
            $('#transaction_amount').on('change', function() {
                var transactionType = $('#transaction_type').val();
                var transactionBalance = parseFloat($(this).val()) || 0;
                var availableBankBalance = parseFloat($("#available_balance").val()) || 0;
                console.log(transactionBalance);
                console.log(availableBankBalance);
                if (transactionBalance > availableBankBalance && transactionType == 1) {

                    Swal.fire({
                        text: "Insufficient funds available",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.reload();
                            // Additional actions if needed
                        }
                    });

                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Listen for click events on submit buttons
            $('#transaction_form').find('button[type="submit"]').on('click', function(event) {
                // Set the value of submit_type to the value attribute of the clicked button
                $('#submission_type').val($(this).val());
            });

            // Listen for form submission
            $('#transaction_form').on('submit', function(event) {
                // Prevent default form submission behavior
                event.preventDefault();

                // Show confirmation dialog
                Swal.fire({
                    text: "Are you sure you want to transaction the amount?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-secondary ml-1"
                    }
                }).then(function(result) {
                    // If user confirms, submit the form
                    if (result.isConfirmed) {
                        // Manually submit the form
                        event.target.submit();
                    }
                });
            });
        });
    </script>
@endsection
