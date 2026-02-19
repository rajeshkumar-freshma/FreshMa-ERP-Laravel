    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>
            $(function() {
                $(".fsh_flat_datepicker").flatpickr();
            });
        </script>
        <script>
            $(document).ready(function() {
                // Function to handle related_to change
                function handleRelatedIdChange() {
                    var selectStoreId = $('#related_to').val();

                    console.log("selectStoreId", selectStoreId);

                    if (selectStoreId == 1) {
                        $('#store_model').show();
                        $('#warehouse_model').hide();
                        $('#store_id').select2(); // Initialize Select2 after showing
                        $('#store_id').trigger('change');
                    } else if (selectStoreId == 2) {
                        $('#store_model').hide();
                        $('#warehouse_model').show();
                        $('#warehouse_id').select2(); // Initialize Select2 after showing
                        $('#warehouse_id').trigger('change');
                    } else {
                        $('#store_model').hide();
                        $('#warehouse_model').hide();
                    }
                }

                // Trigger the function on page load
                $('#related_to').trigger('change');

                // Attach the function to the change event
                $('#related_to').change(handleRelatedIdChange);
            });

            $(document).ready(function() {
                $('#income_expense_type_id').change(function() {
                    var selectedType = $(this).val();
                    var invoiceNumberField = $('#income_expense_invoice_number');


                    if (selectedType == 1) {
                        // Set income invoice number
                        invoiceNumberField.val('{{ $income_invoice_number }}');
                    } else if (selectedType == 2) {
                        // Set expense invoice number
                        invoiceNumberField.val('{{ $expense_invoice_number }}');
                    } else {
                        // Reset the field or handle other types
                        invoiceNumberField.val('');
                    }
                });
            });

            // $(document).ready(function() {
            //     var incomeExpenseContainer = $("#incomeExpenseConatiner");

            //     $('#income_expense_type_id').change(function() {
            //         var selectedType = $(this).val();

            //         $.ajax({
            //             type: 'POST',
            //             url: "{{ route('admin.income_expense_cetegory_get') }}",
            //             data: {
            //                 incomeExpenseTypeId: selectedType,
            //                 '_token': '{{ csrf_token() }}'
            //             },
            //             success: function(data) {
            //                 if (data.status === 200 && Array.isArray(data.data)) {
            //                     incomeexpensedetails(data);
            //                 }
            //             },
            //             error: function(error) {
            //                 console.error('Error fetching payer details:', error);
            //             }
            //         });
            //     });
            // });

            // function incomeexpensedetails(data) {
            //     var categorySelect = $('#category_id');
            //     categorySelect.empty(); // Clear existing options

            //     if (data.status === 200 && data.data.length > 0) {
            //         categorySelect.append('<option value="">Select Category..</option>');

            //         // Loop through the data and add options to the payer select
            //         $.each(data.data, function(index, category) {
            //             categorySelect.append('<option value="' + category.id + '">' + category.name +
            //                 '</option>');
            //         });

            //         // If you want to pre-select an option, you can uncomment and modify the following line
            //         // payerSelect.val(data.data[0].id).trigger('change'); // Trigger change event if needed
            //     } else {
            //         categorySelect.append('<option value="">No Category available</option>');
            //     }

            //     // Trigger the change event to update the Select2 plugin
            //     categorySelect.trigger('change');
            // }

            // $(document).ready(function() {
            //     var incomeExpenseContainer = $("#incomeExpenseConatiner");

            //     $('#income_expense_type_id').change(function() {
            //         var selectedType = $(this).val();

            //         $.ajax({
            //             type: 'POST',
            //             url: "{{ route('admin.income_expense_cetegory_get') }}",
            //             data: {
            //                 incomeExpenseTypeId: selectedType,
            //                 '_token': '{{ csrf_token() }}'
            //             },
            //             success: function(data) {
            //                 if (data.status === 200 && Array.isArray(data.data)) {
            //                     incomeExpenseContainer.empty(); // Clear existing content

            //                     data.data.forEach(function(item, index) {
            //                         var uniqueId = 'item_' + index;

            //                         // Create a div to hold the elements for each item
            //                         var itemContainer = $('<div>', {
            //                             class: 'row mb-2 remove',
            //                             id: uniqueId
            //                         });

            //                         // Create select dropdown
            //                         var selectDropdown = $('<select>', {
            //                             class: 'form-select form-select-sm',
            //                             name: 'income_expense_items[' + index + ']',
            //                             id: uniqueId + '_category'
            //                         });

            //                         // Populate select options
            //                         selectDropdown.append($('<option>', {
            //                             value: item.id,
            //                             text: item.name
            //                         }));

            //                         // Create amount input
            //                         var amountInput = $('<input>', {
            //                             type: 'text',
            //                             class: 'form-control form-control-sm amount-input',
            //                             name: 'income_expense_items[' + index +
            //                                 '][amount]',
            //                             placeholder: 'Enter Amount',
            //                             id: uniqueId + '_amount'
            //                         });

            //                         // Create remarks input
            //                         var remarksInput = $('<input>', {
            //                             type: 'text',
            //                             class: 'form-control form-control-sm',
            //                             name: 'income_expense_items[' + index +
            //                                 '][remarks]',
            //                             placeholder: 'Remarks',
            //                             id: uniqueId + '_remarks'
            //                         });

            //                         // Create remove button
            //                         var removeButton = $('<span>', {
            //                             class: 'input-text border-0 text-danger',
            //                             text: 'X',
            //                             click: function() {
            //                                 itemContainer
            //                                     .remove(); // Remove the entire itemContainer
            //                             }
            //                         });

            //                         // Append elements to itemContainer
            //                         itemContainer.append(selectDropdown);
            //                         itemContainer.append(amountInput);
            //                         itemContainer.append(remarksInput);
            //                         itemContainer.append(removeButton);

            //                         // Append itemContainer to incomeExpenseContainer
            //                         incomeExpenseContainer.append(itemContainer);
            //                     });

            //                     // Add More button
            //                     var addMoreButton = $('<button>', {
            //                         type: 'button',
            //                         class: 'btn btn-success btn-sm',
            //                         text: 'Add More',
            //                         click: function() {
            //                             var clonedContainer = incomeExpenseContainer
            //                                 .children()
            //                                 .first().clone();
            //                             incomeExpenseContainer.append(clonedContainer);
            //                         }
            //                     });

            //                     // Add Add More button to the container
            //                     incomeExpenseContainer.append(addMoreButton);
            //                 }
            //             },
            //             error: function(xhr, status, error) {
            //                 console.error(xhr.responseText);
            //             }
            //         });
            //     });
            // });
        </script>
        <script>
            // Add the script to remove items if needed
            function removeItem(index) {
                $('#item_' + index).remove();
            }
        </script>
    @endsection
