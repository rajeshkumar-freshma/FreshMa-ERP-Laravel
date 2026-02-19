@section('scripts')
    <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>

    <script>
        // $(document).ready(function() {
        //     // Trigger AJAX request on page load to fetch payer details based on selected store ID
        //     var storeId = $("#store_id").val(); // Get the current store ID
        //     fetchPayerDetails(storeId); // Fetch payer details for the current store ID

        //     // Event listener for changing store ID
        //     $("#store_id").on('change', function() {
        //         var storeId = $(this).val();
        //         fetchPayerDetails(storeId); // Fetch payer details for the selected store ID
        //     });

        //     // Function to fetch payer details via AJAX
        //     function fetchPayerDetails(storeId) {
        //         // Make AJAX request to get payer details
        //         $.ajax({
        //             url: "{{ route('admin.getpayerdetails') }}",
        //             type: 'GET',
        //             data: {
        //                 store_id: storeId
        //             },
        //             success: function(response) {
        //                 updatePayerOptions(response);
        //             },
        //             error: function(error) {
        //                 console.error('Error fetching payer details:', error);
        //             }
        //         });
        //     }

        //     // Function to update payer options
        //     function updatePayerOptions(data) {
        //         var payerSelect = $('#payer_id');
        //         payerSelect.empty(); // Clear existing options

        //         if (data.status === 200 && data.data.length > 0) {
        //             payerSelect.append('<option value="">Select Payer..</option>');

        //             // Loop through the data and add options to the payer select
        //             $.each(data.data, function(index, payer) {
        //                 payerSelect.append('<option value="' + payer.id + '">' + payer.name +
        //                     //  ' - '+ payer.user_code +
        //                     '</option>');
        //             });

        //             // Select the appropriate option based on the provided payer_id
        //             var selectedPayerId = "{{ @$cashPaidTooffice->payer_id }}";
        //             payerSelect.val(selectedPayerId).trigger('change'); // Trigger change event if needed
        //         } else {
        //             payerSelect.append('<option value="">No Payer available</option>');
        //         }

        //         // Trigger the change event to update the Select2 plugin
        //         payerSelect.trigger('change');
        //     }
        // });
        $(document).ready(function() {
            var selectedPayerId = $("#selected_payer_id").val(); // Get the selected payer ID

            // Function to fetch payer details via AJAX
            function fetchPayerDetails(storeId) {
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
            }

            // Function to update payer options
            function updatePayerOptions(data) {
                var payerSelect = $('#payer_id');
                payerSelect.empty(); // Clear existing options

                if (data.status === 200 && data.data.length > 0) {
                    payerSelect.append('<option value="">Select Payer..</option>');

                    // Loop through the data and add options to the payer select
                    $.each(data.data, function(index, payer) {
                        payerSelect.append('<option value="' + payer.id + '">' + payer.name + '</option>');
                    });

                    // Select the appropriate option based on the provided payer_id
                    payerSelect.val(selectedPayerId).trigger('change'); // Trigger change event if needed

                } else {
                    payerSelect.append('<option value="">No Payer available</option>');
                }

                // Trigger the change event to update the Select2 plugin
                payerSelect.trigger('change');
            }

            // Trigger AJAX request on page load to fetch payer details based on selected store ID
            var storeId = $("#store_id").val(); // Get the current store ID
            fetchPayerDetails(storeId); // Fetch payer details for the current store ID

            // Event listener for changing store ID
            $("#store_id").on('change', function() {
                var storeId = $(this).val();
                fetchPayerDetails(storeId); // Fetch payer details for the selected store ID
            });


            //function for calculate total amount for denominations
            function calculateTotalAmount() {
                const inputs = document.querySelectorAll('.denomination');
                let total = 0;

                inputs.forEach(input => {
                    const denomination = parseFloat(input.getAttribute('data-denomination')) || 0;
                    const count = parseFloat(input.value) || 0;
                    total += denomination * count;
                });

                document.getElementById('total').value = total.toFixed(2);
                console.log('Total amount:', total);
            }

            document.querySelectorAll('.denomination').forEach(input => {
                input.addEventListener('input', calculateTotalAmount);
            });

            //function for to get a not updated dates
            function fetchLastUpdatedDate(storeId, receiverId,id) {
                if (storeId && receiverId) {
                    $.ajax({
                        url: "{{ route('admin.lastUpdatedDate') }}",
                        type: 'GET',
                        data: {
                            store_id: storeId,
                            receiver_id: receiverId
                        },
                        success: function(response) {
                            if (response.lastUpdatedDate) {
                                $('#lastUpdatedDate').val(response.lastUpdatedDate);
                            } else {
                                $('#lastUpdatedDate').val('No date found.');
                            }
                            var datesHtml = '';
                            var today = new Date().toISOString().split('T')[0];

                            if (!response.notUpdatedDates.includes(today)) {
                                response.notUpdatedDates.push(today);
                            }

                            if (response.notUpdatedDates.length > 0) {
                                response.notUpdatedDates.forEach(function(date) {
                                    datesHtml += '<div class="input-group"><input type="date" name="dates[]"class="date-input form-control" value="' + date + '" placeholder="Date">' + '<br>' +
                                                '<input type="number" name="amounts[]"class="amount-input form-control" placeholder="Amount"></div>' + '<br>';
                                });
                                $('#notUpdatedDates').html(datesHtml);

                                $('.amount-input').on('input', calculateTotal);
                            } else {
                                $('#notUpdatedDates').html('<p>No dates found.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $('#lastUpdatedDate').val('Error fetching date.');
                        }
                    });
                }else if (id) {
                    $.ajax({
                        url: "{{ route('admin.notUpdatedDates', ['id' => ':id']) }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            var datesHtml = '';
                            var today = new Date().toISOString().split('T')[0];

                            if (!response.notUpdatedDates.includes(today)) {
                                response.notUpdatedDates.push(today);
                            }

                            if (response.notUpdatedDates.length > 0) {
                                response.notUpdatedDates.forEach(function(date) {
                                    datesHtml += '<div class="input-group"><input type="date" name="dates[]"class="date-input form-control" value="' + date + '" placeholder="Date">' + '<br>' +
                                                '<input type="number" name="amounts[]"class="amount-input form-control" placeholder="Amount"></div>' + '<br>';
                                });
                                $('#notUpdatedDates').html(datesHtml);

                                $('.amount-input').on('input', calculateTotal);
                            } else {
                                $('#notUpdatedDates').html('<p>No dates found.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $('#notUpdatedDates').val('Error fetching date.');
                        }
                    });
                }
            }

            function calculateTotal() {
                var totalAmount = 0;
                $('.amount-input').each(function() {
                    var amount = parseFloat($(this).val());
                    if (!isNaN(amount)) {
                        totalAmount += amount;
                    }
                });
                $('#amount').val(totalAmount.toFixed(2));
            }

            $(document).ready(function() {
                $('#store_id, #receiver_id').change(function() {
                    var storeId = $('#store_id').val();
                    var receiverId = $('#receiver_id').val();
                    fetchLastUpdatedDate(storeId, receiverId);
                });
            });

            $('.amount-input').change(function() {

                var totalAmount = 0;
                $('.amount-input').each(function() {
                    var amount = parseFloat($(this).val());
                    if (!isNaN(amount)) {
                        totalAmount += amount;
                    }
                });
                $('#amount').val(totalAmount.toFixed(2));
            })
            $('.denomination').on('input', calculateTotalAmount);
        });
    </script>
@endsection
