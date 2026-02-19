<script>
    $(function() {
        var commission_amount = 0;
        $('body').on('keyup', '.quantity, .given_quantity, .amount, .discount_percentage, .discount_amount',
            function() {
                var loopkey = $(this).attr('data-loopkey');
                subTotalCalculation(loopkey);
            }).trigger('keyup');

        $('.quantity').on('keyup', function() {
            var loopkey = $(this).attr('data-loopkey');
            subTotalCalculation(loopkey);
        }).trigger('keyup');

        $('body').on('change', '.request_status', function() {
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-model');
            var status = $(this).find(':selected').val();
            var filter_warehouse_id = $('#filter_warehouse').val();
            var filter_store_id = $('#filter_store').val();
            var filter_vendor_id = $('#filter_vendor').val();
            var filter_status = $('#filter_status').val();
            var from_date = $('#filter_from_date').val();
            var to_date = $('#filter_to_date').val();
            var filter_store_indent_request_id = $('#filter_store_indent_request').val();
            var filter_vendor_indent_request_id = $('#filter_vendor_indent_request').val();

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change the status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.indent_request.status') }}',
                        type: "post",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            type: type,
                            status: status,
                            filter_warehouse_id: filter_warehouse_id,
                            filter_store_id: filter_store_id,
                            filter_vendor_id: filter_vendor_id,
                            filter_status: filter_status,
                            from_date: from_date,
                            to_date: to_date,
                            filter_store_indent_request_id: filter_store_indent_request_id,
                            filter_vendor_indent_request_id: filter_vendor_indent_request_id,
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                    backdrop: true,
                                }).then(function(result) {
                                    if (result.isConfirmed) {
                                        $('.store_indent_status' + id).html(
                                            response.update_status);
                                        $('.overall_cumulative_data').html(
                                            response.overall_data);
                                    }
                                })
                            }
                        },
                    })
                }
            });
        });
    })

    function subTotalCalculation(loopkey) {
        var quantity = parseFloat($('#quantity' + loopkey).val()) || 0;
        var given_quantity = parseFloat($('#given_quantity' + loopkey).val()) || 0;
        var discount_amount = parseFloat($('#discount_amount' + loopkey).val()) || 0;
        var discount_percentage = parseFloat($('#discount_percentage' + loopkey).val()) || 0;
        var discount_percentage = parseFloat($('#discount_percentage' + loopkey).val()) || 0;
        var amount = parseFloat($('#amount' + loopkey).val()) || 0;
        var inc_exp_amount = parseFloat($('#inc_exp_amount' + loopkey).val()) || 0;

        quantity = given_quantity != 0 && given_quantity != null ? given_quantity : quantity

        var sub_total = (parseFloat(amount) + parseFloat(inc_exp_amount));

        var discount_type = $('#discount_type' + loopkey).find(':selected').val();

        $('#discount_percentage_amount' + loopkey).html(thousandseparator(discount_amount, 2));
        $('#discount_percentage_amount_val' + loopkey).val(numbersconvertingtoint(discount_amount, 2));

        $('#sub_total' + loopkey).html(thousandseparator(sub_total, 2));
        $('#sub_total_val' + loopkey).val(numbersconvertingtoint(sub_total, 2));

        tax_amount_and_sub_total(loopkey)
        commission_amount(loopkey)
        totalAmount()
    }

    function commission_amount(loopkey) {
        var commission_percentage = parseFloat($('#commission_percentage_box' + loopkey).val()) || 0;
        var sub_total = $('#sub_total_val' + loopkey).val() || 0;

        var commission_amount = parseFloat((commission_percentage / 100) * sub_total);

        $('#commission_percentage_amount' + loopkey).html(thousandseparator(commission_amount, 2));
        $('#commission_amount_val' + loopkey).val(numbersconvertingtoint(commission_amount, 2));

        var whole_total = parseFloat(sub_total) + parseFloat(commission_amount)

        $('#total' + loopkey).html(thousandseparator(whole_total, 2));
        $('#total_val' + loopkey).val(numbersconvertingtoint(whole_total, 2));
        calcperunitvalue(loopkey)
    }

    function tax_amount_and_sub_total(loopkey) {
        var inc_expense_amount = $('#inc_expense_amount' + loopkey).val() || 0;
        var sub_total = $('#sub_total_val' + loopkey).val() || 0;
        var tax_rate = $('#tax_rate' + loopkey).find(':selected').attr('data-tax_rate') || 0;
        var discount_type = $('#discount_type' + loopkey).find(':selected').val();
        var discount_amount = parseFloat($('#discount_amount' + loopkey).val()) || 0;
        var discount_percentage = parseFloat($('#discount_percentage' + loopkey).val()) || 0;

        sub_total_expense = parseFloat(sub_total) + parseFloat(inc_expense_amount);
        var tax_value = parseFloat((sub_total_expense * tax_rate) / 100);
        $('#tax_value' + loopkey).html(thousandseparator(tax_value, 2));
        $('#tax_val' + loopkey).val(numbersconvertingtoint(tax_value, 2));

        var calc_sub_total = parseFloat(sub_total) + parseFloat(tax_value);

        if (discount_type == 1) {
            var sub_total = parseFloat(calc_sub_total - discount_amount);
        } else {
            var discount_amount = parseFloat((discount_percentage * calc_sub_total) / 100);
            var sub_total = parseFloat((calc_sub_total) - discount_amount);
        }

        $('#sub_total' + loopkey).html(thousandseparator(sub_total, 2));
        $('#sub_total_val' + loopkey).val(numbersconvertingtoint(sub_total, 2));

        calcperunitvalue(loopkey)
    }

    function calcperunitvalue(loopkey) {
        var given_quantity = parseFloat($('#given_quantity' + loopkey).val()) || 0;
        if (given_quantity > 0) {
            var quantity = parseFloat(given_quantity);
        } else {
            var quantity = parseFloat($('#quantity' + loopkey).val()) || 0;
        }

        var get_total_val = parseFloat($('#total_val' + loopkey).val()) || 0;

        if (get_total_val > 0) {
            var total_val = parseFloat(get_total_val);
        } else {
            var total_val = parseFloat($('#sub_total_val' + loopkey).val()) || 0;
        }

        if (quantity > 0) {
            var perProductAmount = parseFloat(total_val) / parseFloat(quantity);
        } else {
            var perProductAmount = 0;
        }
        $('#per_unit_amount' + loopkey).html(thousandseparator(perProductAmount, 2));
        $('#per_unit_amount_val' + loopkey).val(numbersconvertingtoint(perProductAmount, 2));
    }

    function totalAmount() {
        var total_tax_value = 0;
        $('.tax_val').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_tax_value += parseFloat($(this).val());
            }
        });

        $('.total_tax').html(thousandseparator(total_tax_value, 2));
        $('#total_tax_val').val(numbersconvertingtoint(total_tax_value, 2));

        var total_discount = 0;
        $('.discount_percentage_amount_val').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_discount += parseFloat($(this).val());
            }
        });

        $('.total_discount').html(thousandseparator(total_discount, 2));
        $('#total_discount_val').val(numbersconvertingtoint(total_discount, 2));

        var total_quantity = 0;
        $('.quantity').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_quantity += parseFloat($(this).val());
            }
        });

        $('.total_request_quantity').html(thousandseparator(total_quantity, 3));
        $('#total_request_quantity_val').val(numbersconvertingtoint(total_quantity, 3));

        var total_given_quantity = 0;
        $('.quantity').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_given_quantity += parseFloat($(this).val());
            }
        });

        $('.total_given_quantity').html(thousandseparator(total_given_quantity, 3));
        $('#total_given_quantity_val').val(numbersconvertingtoint(total_given_quantity, 3));

        var sub_total_amount = 0;
        $('.sub_total_val').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                sub_total_amount += parseFloat($(this).val());
            }
        });
        $('.sub_total_amount').html(thousandseparator(sub_total_amount, 2));
        $('#sub_total_amount_val').val(numbersconvertingtoint(sub_total_amount, 2));

        var total_amount = 0;
        $('.total_val').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_amount += parseFloat($(this).val());
            }
        });

        if (total_amount > 0) {
            total_amount = total_amount;
        } else {
            $('.sub_total_val').each(function() {
                if ($(this).val() != '' && !isNaN($(this).val())) {
                    total_amount += parseFloat($(this).val());
                }
            });
        }
        $('.total_amount').html(thousandseparator(total_amount, 2));
        $('#total_amount_val').val(numbersconvertingtoint(total_amount, 2));

        var total_expense_amount = 0;
        $('.inc_exp_amount').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_expense_amount += parseFloat($(this).val());
            }
        });
        $('.total_expense_amount').html(thousandseparator(total_expense_amount, 2));
        $('#total_expense_amount_val').val(numbersconvertingtoint(total_expense_amount, 2));

        var total_commission_amount = 0;
        $('.commission_amount_val').each(function() {
            if ($(this).val() != '' && !isNaN($(this).val())) {
                total_commission_amount += parseFloat($(this).val());
            }
        });
        $('.total_commission_amount').html(thousandseparator(total_commission_amount, 2));
        $('#total_commission_amount_val').val(numbersconvertingtoint(total_commission_amount, 2));
    }

    $('body').on('keyup', '.commission_percentage_box', function() {
        var loopkey = $(this).attr('data-loopkey');
        subTotalCalculation(loopkey);
    }).trigger('keyup');

    $('body').on('change', '.tax_rate', function() {
        $('.product_row').each(function(key, index) {
            subTotalCalculation(key);
        })
    }).trigger('keyup');

    $('#ir_vendor').on('change', function() {
        commission_edit_enable()
    }).trigger('change');

    $('body').on('change', ' .discount_type', function() {
        var loopkey = $(this).attr('data-loopkey');
        var discount_type = $('#discount_type' + loopkey).find(':selected').val();
        if (discount_type == 1) {
            $('#discount_amount' + loopkey).css('display', 'block')
            $('#discount_percentage' + loopkey).css('display', 'none')
        } else {
            $('#discount_amount' + loopkey).css('display', 'none')
            $('#discount_percentage' + loopkey).css('display', 'block')
        }
        $('.product_row').each(function(key, index) {
            subTotalCalculation(key);
        })
    }).trigger('keyup');

    function commission_edit_enable() {
        var vendor_percentage = $('#ir_vendor').find(':selected').attr('data-vendor_percentage') || 0;
        var is_editable = $('#ir_vendor').find(':selected').attr('data-editable') || 0;

        $('.commission_percentage_box').val(vendor_percentage);

        if (is_editable == 1) {
            $('.commission_percentage_box').prop('readonly', false);
        } else {
            $('.commission_percentage_box').prop('readonly', true);
        }

        $('.product_row').each(function(key, index) {
            subTotalCalculation(key);
        })
    }
</script>
