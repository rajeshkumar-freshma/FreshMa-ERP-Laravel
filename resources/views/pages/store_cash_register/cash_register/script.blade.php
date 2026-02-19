@section('scripts')
    <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#store_id").on('change', function() {
                var storeId = $(this).val();
                // Make AJAX request to get payer details
                $.ajax({
                    url: "{{ route('admin.getpayerdetails') }}",
                    type: 'GET',
                    data: {
                        store_id: storeId
                    },
                    success: function(response) {
                        updatePayerOptions(response);
                    },

                    error: function(error) {
                        console.error('Error fetching payer details:', error);
                    }
                });
            });

            // // Function to update receiver options
            // function updateReceiverOptions(data) {
            //     // Assuming you have a function to update the receiver select options
            //     // Modify this based on how your HTML is structured
            //     // For example, if receiver_id is an input, you can set its value like:
            //     // $('#receiver_id').val(data.receiver_id);
            // }
        });
        // Function to update payer options
        function updatePayerOptions(data) {
            var payerSelect = $('#payer_id');
            payerSelect.empty(); // Clear existing options

            if (data.status === 200 && data.data.length > 0) {
                payerSelect.append('<option value="">Select Payer..</option>');

                // Loop through the data and add options to the payer select
                $.each(data.data, function(index, payer) {
                    payerSelect.append('<option value="' + payer.id + '">' + payer.name + ' - ' + payer.user_code +
                        '</option>');
                });

                // If you want to pre-select an option, you can uncomment and modify the following line
                // payerSelect.val(data.data[0].id).trigger('change'); // Trigger change event if needed
            } else {
                payerSelect.append('<option value="">No Payer available</option>');
            }

            // Trigger the change event to update the Select2 plugin
            payerSelect.trigger('change');
        }
    </script>
@endsection
