<script>
    $(function() {
        var count = $('.add_more_product_pin_data').length || 1;

        $('body').on('click', '.add_product_pin_data', function() {

            var product_pin_append_content =
            '<div class="row mb-3 product_pin_data'+count+'"> <hr>' +
                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="product_id" class="form-label">{{ __("Product") }}</label>' +
                        '<div class="input-group input-group-sm flex-nowrap">' +
                            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                            '<div class="overflow-hidden flex-grow-1">' +
                                '<select name="product_pin_details[product_id][]" id="product_id" aria-label="{{ __("Select Product") }}" data-control="select2" data-placeholder="{{ __("Select Product..") }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">' +
                                    '<option value="">{{ __("Select Product..") }}</option>' +
                                    '@foreach ($products as $key => $item)' +
                                        '<option data-kt-flag="{{ $item->id }}" value="{{ $item->id }}"> {{ $item->name }}</option>' +
                                    '@endforeach' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="box_no" class=" form-label">{{ __("Box No") }}</label>' +
                        '<input id="box_no" type="text" class="form-control form-control-sm form-control-solid" name="product_pin_details[box_no][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="box_weight" class=" form-label">{{ __("Weight") }}</label>' +
                        '<input id="box_weight" type="text" class="form-control form-control-sm form-control-solid" name="product_pin_details[box_weight][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="to_location" class=" form-label"> &nbsp;</label>' +
                        '<button type="button" class="btn btn-sm btn-primary add_product_pin_data mt-7"><i class="fa fa-plus"></i></button>&nbsp; ' +
                        '<button type="button" class="btn btn-sm btn-danger product_pin_remove_date mt-7" data-loop=' + count + '><i class="fa fa-close"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';

            $('.product_pin_data').append(product_pin_append_content);

            $('.select2-container').remove();
            $('.form-select').select2();
            count++;
        })

        $('body').on('click', '.product_pin_remove_date', function() {
            var loopkey = $(this).attr('data-loop');
            $('.product_pin_data'+loopkey).remove();
        });
    })
</script>
