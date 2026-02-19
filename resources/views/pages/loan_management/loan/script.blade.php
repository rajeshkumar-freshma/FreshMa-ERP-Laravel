@section('scripts')
    @include('pages.partials.date_picker')

    <script>
        function getLoanCategoryDetails() {
            var loanCategroyId = $('#loan_category_id').val();
            console.log(loanCategroyId);
            // var formData = new FormData();
            // formData.append('leave_type_name', leaveTypeName);
            // formData.append('leave_type_status', leaveTypeStatus);
            // var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            // formData.append('_token', csrfToken);

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.getLoanCategoryDetails') }}",
                data: {
                    loanCategroyId: loanCategroyId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Handle the successful response here
                    if (response.status === 200) {
                        console.log('Loan Category Details:', response.loan_categroy_details);
                        var details = response.loan_categroy_details;
                        // Assuming details.amount is the loan amount, details.interest_rate is the interest rate, and details.loan_tenure is the loan tenure
                        // var loanAmount = details.amount; // Replace with your actual variable
                        // var interestRate = details.interest_rate / 100 /12;// Convert annual interest rate to monthly and to decimal
                        // var loanTenureInMonths = details.loan_tenure; // Replace with your actual variable

                        // // Calculate EMI using the formula
                        // var emi = loanAmount * interestRate * Math.pow((1 + interestRate), loanTenureInMonths) /
                        //     (Math.pow((1 + interestRate), loanTenureInMonths) - 1);

                        // console.log('EMI:', emi);

                        // Set values for the input fields
                        $('#loan_tenure').val(details.loan_tenure);
                        $('#principal_amount').val(details.amount);
                        $('#loan_term').val(details.loan_term);
                        $('#loan_term_method').val(details.loan_term_method).trigger('change');
                        $('#interest_rate').val(details.interest_rate);
                        $('#interest_type').val(details.interest_type).trigger('change');
                        $('#interest_frequency').val(details.interest_frequency).trigger('change');
                        $('#repayment_frequency').val(details.repayment_frequency).trigger('change');
                        $('#late_payment_penalty_rate').val(details.late_payment_penalty_rate);
                        $('#charges').val(details.charges).trigger('change');
                        $('#description').val(details.description);
                        $('#status').val(details.status).trigger('change');

                        // Attach event listeners to relevant input fields
                        $('#principal_amount, #interest_rate, #loan_tenure, #repayment_frequency').on('input',
                            calculateEMI);

                        function calculateEMI() {
                            var loanAmount = parseFloat($('#principal_amount').val()) ||
                                0; // Use principal_amount instead of loan_amount
                            var interestRate = (parseFloat($('#interest_rate').val()) || 0) / 100 /
                                12; // Monthly interest rate
                            console.log("interestrate");
                            console.log(interestRate);
                            var loanTenure = parseInt($('#loan_tenure').val()) || 0;

                            // Additional logic for repayment frequency if needed
                            var repaymentFrequency = parseInt($('#repayment_frequency').val()) || 0;
                            // Modify interestRate based on repaymentFrequency if needed

                            var emi = loanAmount * interestRate * Math.pow(1 + interestRate, loanTenure) /
                                (Math.pow(1 + interestRate, loanTenure) - 1);

                            // Update the repayment_amount field
                            $('#repayment_amount').val(emi.toFixed(2));
                        }

                        // Trigger the initial calculation on page load
                        calculateEMI();


                        // $('#repayment_amount').val(emi);


                    } else {
                        console.error('Error:', response.message);
                    }
                },
                error: function(error) {
                    // Handle the error response here
                    console.error('Ajax Error:', error);
                }
            });
        }
    </script>
    <script>
        // Function to hide/show divs based on loan type
        function hideFormEmployee() {
            var loanTypeId = $('#loan_type_id').val();

            // Hide both divs initially
            $('#employee_id_div').hide();
            $('#bank_id_div').hide();

            // Show/hide div based on loanTypeId
            if (loanTypeId == 1) {
                $('#employee_id_div').hide();
                $('#bank_id_div').show();
            } else if (loanTypeId == 2) {
                $('#bank_id_div').hide();
                $('#employee_id_div').show();
            }
        }

        // Call the function on page load
        $(document).ready(function() {
            hideFormEmployee();

            // Bind the function to the onchange event of loan_type_id
            $('#loan_type_id').on('change', function() {
                hideFormEmployee();
            });
        });
    </script>
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
        function StoreBankAccount(loanId) {
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
    </script> --}}
    <script>
        function confirmBankDetails() {
            $('#bankDetailsModal').modal('show');
        }
        $(document).ready(function() {
            // Add click event listener to the Close button inside the modal
            $('#closeButton').on('click', function() {
                $('#bankDetailsModal').modal('hide'); // Close the modal
            });
            $('#bankDetailsForm').submit(function(e) {
                e.preventDefault();
                // Make an AJAX request to storeBankAccount
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.storeBankAccount') }}",
                    data: $('#bankDetailsForm').serialize(),
                    success: function(response) {
                        if (response.status === 200) {
                            // Update the bank dropdown options
                            updateBankDropdown(response.data);
                            $('#bankDetailsModal').modal('hide');
                        }

                        // Optionally, you can display a success message or perform other actions
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            });

            // Function to update the bank dropdown options and reload bank_id_div
            function updateBankDropdown(bankData) {
                var bankDropdown = $('#bank_id');
                bankDropdown.empty(); // Clear existing options

                // Add new options based on the response data
                $.each(bankData, function(key, value) {
                    bankDropdown.append($('<option>', {
                        value: value.id,
                        text: value.bank_name,
                    }));
                });

                // Select the first option if available
                if (bankData.length > 0) {
                    bankDropdown.val(bankData[0].id).trigger('change');
                }

                // Reload the bank_id_div
                $('#reload_div').load(location.href + ' #reload_div');
            }

        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            // Intercept the form submission
            $('#bankDetailsForm').submit(function(e) {
                e.preventDefault();

                // Make an AJAX request to storeBankAccount
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.storeBankAccount') }}",
                    data: $('#bankDetailsForm').serialize(),
                    success: function(response) {
                        if (response.status === 200) {
                            // Assuming 'data' in the response contains the updated options
                            updateBankDropdown(response.data);
                        }

                        // Optionally, you can display a success message or perform other actions
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });

                // Close the modal after submitting
                $('#bankDetailsModal').modal('hide');
            });

            // Function to update the bank dropdown options
            function updateBankDropdown(bankData) {
                var bankDropdown = $('#bank_id');
                bankDropdown.empty(); // Clear existing options

                // Add new options based on the response data
                $.each(bankData, function(key, value) {
                    bankDropdown.append($('<option>', {
                        value: value.id,
                        text: value.first_name + ' ' + value.last_name
                    }));
                });
            }
        });
    </script> --}}
@endsection
