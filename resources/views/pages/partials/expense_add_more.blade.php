<script>
    $(document).ready(function(){

        $(function() {
            var total_expense_amount = 0;
            var expense_product_count = 0;
            var count = $('.add_more_expense_data').length || 1;

            $('body').on('click', '.add_expense_data', function() {
                var expense_append_content =
                    '<div class="row mb-3 add_more_expense_data append_expense_data' + count +
                    '">' +
                    '<div class="col-md-4">' +
                    '<input type="hidden" class="form-control form-control-sm form-control-solid expense_id" name="expense[expense_id][]" data-loop=' +
                    count + ' id="expense_id' + count + '"/>' +
                    '<div class="mb-5">' +
                    '<label class="form-label">{{ __('Expense Type') }}</label>' +
                    '<div class="input-group input-group-sm flex-nowrap">' +
                    '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                    '<div class="overflow-hidden flex-grow-1">' +
                    '<select name="expense[expense_type_id][]" data-loop=' + count +
                    ' id="expense_type' + count +
                    '" aria-label="{{ __('Select Expense Type') }}" data-control="select2" data-placeholder="{{ __('Select Expense Type..') }}" class="form-select form-select-sm form-select-solid expense_type" data-allow-clear="true">' +
                    '<option value="">{{ __('Select Expense Type..') }}</option>' +
                    @foreach ($expense_types as $key => $expense_type)
                        '<option data-kt-flag="{{ $expense_type->id }}" value="{{ $expense_type->id }}"> {{ $expense_type->name }}</option>' +
                    @endforeach
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="col-md-3">' +
                '<div class="mb-5">' +
                '<label class=" form-label">{{ __('Expense Amount') }}</label>' +
                '<input type="text" class="form-control form-control-sm form-control-solid expense_amount" id="expense_amount' +
                count + '" name="expense[expense_amount][]" data-loop=' + count + ' />' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label class=" form-label">{{ __('Is Billable') }}</label><br>' +
                    '<input type="checkbox" class="form-check-input expense_billable" id="is_billable' +
                    count + '" name="expense[is_billable][]" data-loop=' + count +
                    ' value="1" />' +
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

                $('.append_expense_details').append(expense_append_content);

                $('.select2-container').remove();
                $('.form-select').select2();
                count++;
            })

            $('body').on('click', '.expense_billable', function() {
                $('.add_more_expense_data').each(function(key, index) {
                    sumofexpenseamount(key);
                })
            });

            $('.expense_billable_all_checkbox').on('click', function() {
                expense_product_count = 0;
                $('.expense_billable_product_checkbox').each(function() {
                    if ($('.expense_billable_all_checkbox')[0].checked) {
                        $(this).prop('checked', true);
                        expense_product_count++;
                    } else {
                        $(this).prop('checked', false);
                    }
                });
                expense_billable_calc();
            });

            $('.expense_billable_product_checkbox').on('click', function() {
                var expense_product_count = 0;
                $('.expense_billable_product_checkbox').each(function() {
                    if ($(this)[0].checked) {
                        $(this).prop('checked', true);
                        expense_product_count++;
                    } else {
                        $(this).prop('checked', false);
                    }
                });
                expense_billable_calc();
            })

            $('body').on('keyup', '.expense_amount', function() {
                $('.add_more_expense_data').each(function(key, index) {
                    sumofexpenseamount(key);
                })
            });

            $('.add_more_expense_data').each(function(key, index) {
                sumofexpenseamount(key);
            })

            $('body').on('click', '.remove_expense_data', function() {
                var loopkey = $(this).attr('data-loop');
                $('.append_expense_data' + loopkey).remove();

                $('.add_more_expense_data').each(function(key, index) {
                    sumofexpenseamount(key);
                })
            });

            function sumofexpenseamount(loopkey) {
                // Total Expense Amount
                var sum_of_total_expense_amount = 0;
                $('.expense_amount').each(function() {
                    if ($(this).val() != '' && !isNaN($(this).val())) {
                        sum_of_total_expense_amount += parseFloat($(this).val());
                    }
                });

                $('.total_expense_amount_display').html(thousandseparator(parseFloat(
                    sum_of_total_expense_amount), 2));
                $('#total_expense_amount_display_val').val(numbersconvertingtoint(
                    sum_of_total_expense_amount, 2));

                // Total Billable Expense Amount
                var total_expense_amount = 0;
                $('.expense_billable').each(function(key, index) {
                    var loopkey = $(this).attr('data-loop');
                    if ($(this)[0].checked == true) {
                        var expense_amount = $('#expense_amount' + loopkey).val() || 0;

                        total_expense_amount = parseFloat(total_expense_amount) + parseFloat(expense_amount);
                    }
                });

                $('.total_expense_billable_amount').html(thousandseparator(total_expense_amount, 2));
                $('#total_expense_billable_amount_val').val(numbersconvertingtoint(total_expense_amount,2));

                expense_billable_calc();
            }

            function expense_billable_calc() {
                var expense_product_count = $('.expense_billable_product_checkbox:checked').length || 0;

                var total_expense_amount = parseFloat($('#total_expense_billable_amount_val').val()) ||
                    0;
                if (expense_product_count > 0) {
                    var per_product_expense = parseFloat(total_expense_amount) / parseFloat(
                        expense_product_count);
                } else {
                    var per_product_expense = 0;
                }
                console.log(expense_product_count);
                $('.expense_billable_product_checkbox').each(function() {
                    var productLoop = $(this).attr('data-loopkey');
                    if ($(this).prop('checked') == true) {
                        var sub_total_for_product = $('#sub_total_val' + productLoop).val() ||
                        0;
                        var add_expense_with_subtotal = parseFloat(sub_total_for_product) +
                            parseFloat(per_product_expense);

                        $('#sub_total' + productLoop).html(thousandseparator(
                            add_expense_with_subtotal, 2));
                        $('#sub_total_val' + productLoop).val(numbersconvertingtoint(
                            add_expense_with_subtotal, 2));

                        $('#inc_exp_amount' + productLoop).val(numbersconvertingtoint(
                            per_product_expense, 2));
                        $('#inc_expense_amount' + productLoop).html(thousandseparator(
                            per_product_expense, 2));

                        $('.product_row').each(function(key, index) {
                            subTotalCalculation(key);
                        })
                    } else {
                        var sub_total_for_product = $('#sub_total_val' + productLoop).val() ||
                        0;
                        var add_expense_with_subtotal = parseFloat(sub_total_for_product);

                        $('#sub_total' + productLoop).html(thousandseparator(
                            add_expense_with_subtotal, 2));
                        $('#sub_total_val' + productLoop).val(numbersconvertingtoint(
                            add_expense_with_subtotal, 2));

                        $('#inc_exp_amount' + productLoop).val(numbersconvertingtoint(
                            per_product_expense, 2));
                        $('#inc_expense_amount' + productLoop).html(thousandseparator(
                            per_product_expense, 2));

                        $('.product_row').each(function(key, index) {
                            subTotalCalculation(key);
                        })
                        $(this).prop('checked', false);
                    }
                });
            }
        });
    });
</script>
