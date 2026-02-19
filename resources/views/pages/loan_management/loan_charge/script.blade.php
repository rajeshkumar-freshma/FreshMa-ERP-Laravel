@section('scripts')
    @include('pages.partials.date_picker')

    <<script>
        $(document).ready(function() {

            // Function to get loan details
            function getLoanDetails() {
                var loanId = $('#loan_id').val();

                // Check if the loanId is not empty
                if (loanId) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('admin.getLoanDetails') }}",
                        data: {
                            loanId: loanId,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                updateLoanDetails(response.data);
                            } else {
                                console.error('Error:', response.message);
                            }
                        },
                        error: function(error) {
                            console.error('Ajax Error:', error);
                        }
                    });
                }
            }

            // Function to update loan details on success
            function updateLoanDetails(details) {
                console.log('Loan Details:', details);

                $('#payment_date').val(details.first_payment_date);
                $('#instalment_amount').val(details.repayment_amount);
                $('#due_amount').val(details.repayment_amount);

                // Bind input events
                $('#instalment_amount, #pay_amount').on('input', calculateDueAmount);

                // Initial calculation
                calculateDueAmount();
            }

            // Function to calculate due amount
            function calculateDueAmount() {
                var payAmount = parseFloat($('#pay_amount').val()) || 0;
                var instalmentAmount = parseFloat($('#instalment_amount').val()) || 0;
                var dueAmount = instalmentAmount - payAmount;

                $('#due_amount').val(dueAmount.toFixed(2));
            }

            // Attach the getLoanDetails function to the change event of the loan_id select element
            $('#loan_id').on('change', getLoanDetails);

        });
    </script>
@endsection
