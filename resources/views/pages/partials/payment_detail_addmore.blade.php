<script>
    $(function() {
        var count = $('.add_more_payment_data').length || 1;

        $('body').on('click', '.add_payment_data', function() {

            var payment_append_content =
            '<div class="row mb-3 append_payment_data'+count+'"> <hr>' +
                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="payment_type" class="form-label">{{ __("Payment Type") }}</label>' +
                        '<div class="input-group input-group-sm flex-nowrap">' +
                            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                            '<div class="overflow-hidden flex-grow-1">' +
                                '<select name="payment_details[payment_type_id][]" id="payment_type" aria-label="{{ __("Select Transport Type") }}" data-control="select2" data-placeholder="{{ __("Select Transport Type..") }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">' +
                                    '<option value="">{{ __("Select Transport Type..") }}</option>' +
                                    '@foreach ($payment_types as $key => $payment_type)' +
                                        '<option data-kt-flag="{{ $payment_type->id }}" value="{{ $payment_type->id }}"> {{ $payment_type->payment_type }}</option>' +
                                    '@endforeach' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="transaction_datetime" class=" form-label">{{ __("Transaction Date") }}</label>' +
                        '<input id="transaction_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="payment_details[transaction_datetime][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="transaction_amount" class=" form-label">{{ __("Amount") }}</label>' +
                        '<input id="transaction_amount" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[transaction_amount][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="remark" class=" form-label">{{ __("Remark") }}</label>' +
                        '<textarea id="remark" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[remark][]"></textarea>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="attachments" class=" form-label">{{ __("Attachments") }}</label>' +
                        '<input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="payment_details[payment_transaction_documents][]" />' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="to_location" class=" form-label"> &nbsp;</label>' +
                        '<button type="button" class="btn btn-sm btn-primary add_payment_data mt-7"><i class="fa fa-plus"></i></button>&nbsp; ' +
                        '<button type="button" class="btn btn-sm btn-danger remove_payment_data mt-7" data-loop=' + count + '><i class="fa fa-close"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';

            $('.append_payment_data').append(payment_append_content);

            $(".fsh_flat_datepicker").flatpickr();

            $(".datetime_picker").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });

            $('.select2-container').remove();
            $('.form-select').select2();
            count++;
        })

        $('body').on('click', '.remove_payment_data', function() {
            var loopkey = $(this).attr('data-loop');
            $('.append_payment_data'+loopkey).remove();
        });
    })
</script>
