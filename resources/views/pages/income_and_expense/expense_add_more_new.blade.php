{{-- <script>
    $(function() {
        var options = null // Declare option globally
        //  var options = null; // Declare option globally

        //  var options = []; // Declare option globally
        var options = null; // Declare option globally

        $(document).ready(function() {
            $('#income_expense_type_id').change(function() {
                var selectedType = $(this).val();
                console.log("selectedType");
                console.log(selectedType);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.income_expense_cetegory_get') }}",
                    data: {
                        incomeExpenseTypeId: selectedType,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status === 200 && Array.isArray(data.data)) {
                            var count = $('.add_more_expense_data').length || 1;

                            // Assuming the first dropdown has an ID like "expense_type0"
                            var expenseTypeDropdown = $('#expense_type0');

                            // Clear existing options
                            expenseTypeDropdown.empty();

                            // Add a default option
                            expenseTypeDropdown.append(
                                '<option value="">{{ __('Select Expense Type..') }}</option>'
                            );

                            // Add options based on the returned data
                            options = data.data.map(function(expense_type) {
                                return $('<option>', {
                                    'data-kt-flag': expense_type.id,
                                    'value': expense_type.id,
                                    'text': expense_type.name
                                });
                            });


                            // Append options to the dropdown
                            expenseTypeDropdown.append(options);
                            // Refresh Select2 after updating options
                            expenseTypeDropdown.trigger('change');

                        }
                    },
                    error: function(error) {
                        console.error('Error fetching expense_type details:',
                            error);
                    }
                });
            });


            $('body').on('click', '.add_expense_data', function() {
                var count = $('.add_expense_data').length || 1;
                var expense_append_content =
                    '<div class="row mb-3 add_more_expense_data append_expense_data' + count +
                    '">' +
                    '<div class="col-md-4">' +
                    '<input type="hidden" class="form-control form-control-sm form-control-solid expense_id" name="expense[expense_id][]" data-loop=' +
                    count + ' id="expense_id' + count + '" />' +
                    '<div class="mb-5">' +
                    '<label class="form-label">{{ __('Expense Type') }}</label>' +
                    '<div class="input-group input-group-sm flex-nowrap">' +
                    '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                    '<div class="overflow-hidden flex-grow-1">' +
                    '<select name="expense[expense_type_id][]" data-loop=' + count +
                    ' id="expense_type' +
                    count +
                    '" aria-label="{{ __('Select Income/Expense Type') }}" data-control="select2" data-placeholder="{{ __('Select Income/Expense Type..') }}" class="form-select form-select-sm form-select-solid expense_type" data-allow-clear="true">' +
                    '<option value="">{{ __('Select Income/Expense Type..') }}</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label class=" form-label">{{ __('Income/Expense Amount') }}</label>' +
                    '<input type="text" class="form-control form-control-sm form-control-solid expense_amount" id="expense_amount' +
                    count + '" name="expense[expense_amount][]" data-loop=' + count + ' />' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label class=" form-label">{{ __('Remarks') }}</label><br>' +
                    '<textarea name="expense[remarks][]" data-loop=' + count +
                    ' class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" placeholder="remarks">{{ old('remarks') }}</textarea>' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<div class="mb-5">' +
                    '<label for="transport_number" class=" form-label">&nbsp;</label>' +
                    '<br>' +
                    '<button type="button" class="btn btn-sm btn-primary add_expense_data"><i class="fa fa-plus"></i></button>&nbsp; ' +
                    '<button type="button" class="btn btn-sm btn-danger remove_expense_data" data-loop=' +
                    count + '><i class="fa fa-close"></i></button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                $('#expense_type' + count).append(options);
                $('.append_expense_details').append(expense_append_content);
                $('.select2-container').remove();
                $('.form-select').select2();
                // Refresh Select2 after updating options
                $('#expense_type' + count).trigger('change');
                count++;
            });
        });

        // Define the thousandSeparator function
        function thousandSeparator(number, decimals) {
            var parts = number.toFixed(decimals).toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }


        function numbersConvertingToInt(number, decimals) {
            var parts = number.toFixed(decimals).toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parseInt(parts.join(""), 10);
        }

        // Now you can use the sumOfExpenseAmount function
        $('body').on('keyup', '.expense_amount', function() {
            sumOfExpenseAmount();
        });

        function sumOfExpenseAmount() {
            // Total Expense Amount
            console.log('Total Expense Amount');
            var sumOfTotalExpenseAmount = 0;
            $('.expense_amount').each(function() {
                var amount = parseFloat($(this).val()) || 0;
                sumOfTotalExpenseAmount += amount;
            });

            // Use the thousandSeparator function
            $('.total_expense_amount_display').html(thousandSeparator(parseFloat(sumOfTotalExpenseAmount), 2));
            $('#total_expense_amount_display_val').val(numbersConvertingToInt(sumOfTotalExpenseAmount, 2));
        }

        $('body').on('click', '.remove_expense_data', function() {
            var loopKey = $(this).data('loop');
            $('.append_expense_data' + loopKey).remove();
            sumOfExpenseAmount();
        });
    });

    $(document).ready(function() {
        // Function to handle related_to change
        $('#related_to').change(function() {
            var selectStoreId = $(this).val();
            console.log(selectStoreId);
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
        }).trigger('change');
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
</script> --}}
<script>
    $(function() {
        var options = null // Declare option globally
        //  var options = null; // Declare option globally

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

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.income_expense_cetegory_get') }}",
                    data: {
                        incomeExpenseTypeId: selectedType,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status === 200 && Array.isArray(data.data)) {
                            options =
                                '<option value="">{{ __('Select Expense Type..') }}</option>'; // Declare option globally
                            (data.data).forEach(function(expense_type) {
                                options += '<option data-kt-flag="' +
                                    expense_type.id + '" value="' +
                                    expense_type.id + '"> ' + expense_type
                                    .name + '</option>';
                            });
                        }
                        var count = $('.add_expense_data').length || 0;
                        for (let index = 0; index <= count; index++) {
                            $('#expense_type' + index).html(options);
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching expense_type details:',
                            error);
                    }
                });
            });


            $('body').on('click', '.add_expense_data', function() {
                var count = $('.add_expense_data').length || 1;
                var expense_append_content =
                    '<div class="row mb-3 add_more_expense_data append_expense_data' + count +
                    '">' +
                    '<div class="col-md-4">' +
                    '<input type="hidden" class="form-control form-control-sm form-control-solid expense_id" name="expense[expense_id][]" data-loop=' +
                    count + ' id="expense_id' + count + '" />' +
                    '<div class="mb-5">' +
                    '<label class="form-label required">{{ __('Expense Type') }}</label>' +
                    '<div class="input-group input-group-sm flex-nowrap">' +
                    '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                    '<div class="overflow-hidden flex-grow-1">' +
                    '<select name="expense[expense_type_id][]" data-loop=' + count +
                    ' id="expense_type' +
                    count +
                    '" aria-label="{{ __('Select Expense Type') }}" data-control="select2" data-placeholder="{{ __('Select Expense Type..') }}" class="form-select form-select-sm form-select-solid expense_type" data-allow-clear="true" required>' +
                    '<option value="">{{ __('Select Expense Type..') }}</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label class=" form-label required">{{ __('Expense Amount') }}</label>' +
                    '<input type="text" class="form-control form-control-sm form-control-solid expense_amount" required id="expense_amount' +
                    count + '" name="expense[expense_amount][]" data-loop=' + count + ' />' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label class=" form-label required">{{ __('Remarks') }}</label><br>' +
                    '<textarea name="expense[remarks][]" data-loop=' + count +
                    ' class="form-control form-control-solid form-control-lg mb-3 mb-lg-0" placeholder="remarks">{{ old('remarks') }}</textarea>' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<div class="mb-5">' +
                    '<label for="transport_number" class=" form-label">&nbsp;</label>' +
                    '<br>' +
                    '<button type="button" class="btn btn-sm btn-primary add_expense_data"><i class="fa fa-plus"></i></button>&nbsp; ' +
                    '<button type="button" class="btn btn-sm btn-danger remove_expense_data" data-loop=' +
                    count + '><i class="fa fa-close"></i></button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                console.log("options");
                console.log(options);
                $('.append_expense_details').append(expense_append_content);
                $('#expense_type' + count).html(options);

                $('.select2-container').remove();
                $('.form-select').select2();
                count++;
            });
        });

        // Define the thousandSeparator function
        function thousandSeparator(number, decimals) {
            var parts = number.toFixed(decimals).toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }

        // function numbersConvertingToInt(number, decimals) {
        //     var parts = number.toFixed(decimals).toString().split(".");
        //     parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //     return parseInt(parts.join(""), 10);
        // }
        function numbersConvertingToInt(number, decimals) {
            var parts = number.toFixed(decimals).toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parseFloat(parts.join("."));
        }


        // Now you can use the sumOfExpenseAmount function
        $('body').on('keyup', '.expense_amount', function() {
            sumOfExpenseAmount();
        });

        function sumOfExpenseAmount() {
            // Total Expense Amount
            console.log('Total Expense Amount');
            var sumOfTotalExpenseAmount = 0;
            $('.expense_amount').each(function() {
                var amount = parseFloat($(this).val()) || 0;
                sumOfTotalExpenseAmount += amount;
            });
            console.log("expense_amountexpense_amount");
            console.log($('.expense_amount').val());
            console.log("sumOfTotalExpenseAmount");
            console.log(sumOfTotalExpenseAmount);
            // Use the thousandSeparator function
            $('.total_expense_amount_display').html(thousandSeparator(parseFloat(sumOfTotalExpenseAmount)));
            $('#total_expense_amount_display_val').val(sumOfTotalExpenseAmount);
            console.log("expense amount");
            console.log($('#total_expense_amount_display_val').val());
        }

        $('body').on('click', '.remove_expense_data', function() {
            var loopKey = $(this).data('loop');
            $('.append_expense_data' + loopKey).remove();
            sumOfExpenseAmount();
        });
    });

    $(document).ready(function() {
        // Function to handle related_to change
        $('#related_to').change(function() {
            var selectStoreId = $(this).val();
            console.log(selectStoreId);
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
        }).trigger('change');
    });
</script>
