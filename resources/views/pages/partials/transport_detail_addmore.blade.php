<script>
    $(function() {
        var count = $('.add_more_transport_tracking_data').length || 1;

        $('body').on('click', '.add_transport_tracking_data', function() {
            var transport_tracking_append_content =
            '<div class="row mb-3 append_transport_tracking_data'+count+'"> <hr>' +
                '<div class="col-md-4">' +
                    '<input type="hidden" name="transport_tracking[transport_tracking_id][]" value="{{ @$transport_detail->id }}" id="transport_tracking_id" class="form-control form-control-sm">' +
                    '<div class="mb-5">' +
                        '<label for="transport_type" class="form-label">{{ __("Transport Type") }}</label>' +
                        '<div class="input-group input-group-sm flex-nowrap">' +
                            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                            '<div class="overflow-hidden flex-grow-1">' +
                                '<select name="transport_tracking[transport_type_id][]" id="transport_type" aria-label="{{ __("Select Transport Type") }}" data-control="select2" data-placeholder="{{ __("Select Transport Type..") }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">' +
                                    '<option value="">{{ __("Select Transport Type..") }}</option>' +
                                    '@foreach ($transport_types as $key => $transport_type)' +
                                        '<option data-kt-flag="{{ $transport_type->id }}" value="{{ $transport_type->id }}"> {{ $transport_type->transport_type }}</option>' +
                                    '@endforeach' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="transport_name" class=" form-label">{{ __("Transport Name") }}</label>' +
                        '<input id="transport_name" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_name][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="transport_number" class=" form-label">{{ __("Transport Number") }}</label>' +
                        '<input id="transport_number" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_number][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="departure_datetime" class=" form-label">{{ __("Departure Date") }}</label>' +
                        '<input id="departure_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="transport_tracking[departure_datetime][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="arriving_datetime" class=" form-label">{{ __("Arriving Date") }}</label>' +
                        '<input id="arriving_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="transport_tracking[arriving_datetime][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="attachments" class=" form-label">{{ __("Attachments") }} {!! commoncomponent()->attachment_view(@$transport_detail->image_full_url) !!}</label>' +
                        '<input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_tracking_file][]" />' +
                    '</div>' +
                '</div>' +


                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="from_location" class=" form-label">{{ __("From Location") }}</label>' +
                        '<input id="from_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[from_location][]"/>' +
                    '</div>' +
                '</div>' +


                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="to_location" class=" form-label">{{ __("To Location") }}</label>' +
                        '<input id="to_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[to_location][]"/>' +
                    '</div>' +
                '</div>' +

                '<div class="col-md-4">' +
                    '<div class="mb-5">' +
                        '<label for="to_location" class=" form-label"> &nbsp;</label>' +
                        '<button type="button" class="btn btn-sm btn-primary add_transport_tracking_data mt-7"><i class="fa fa-plus"></i></button>&nbsp; ' +
                        '<button type="button" class="btn btn-sm btn-danger remove_transport_tracking_data mt-7" data-loop=' + count + '><i class="fa fa-close"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';

            $('.append_transport_tracking_data').append(transport_tracking_append_content);

            $('.select2-container').remove();
            $('.form-select').select2();
            $(".fsh_flat_datepicker").flatpickr();

            $(".datetime_picker").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });

            count++;
        })

        $('body').on('click', '.remove_transport_tracking_data', function() {
            var loopkey = $(this).attr('data-loop');
            $('.append_transport_tracking_data'+loopkey).remove();
        });
    })
</script>
