<script>
    $(function() {
        $('#usertype-dropdown').on('change', function() {
            var user_type = $(this).find(':selected').val();
            showpartnertype(user_type);
        }).trigger('change');

        function showpartnertype(user_type) {
            $('#add_partner_detail').show();
            if (user_type == 1) {
                $('#add_partner_detail').hide();
                $('.assign_warehouse_content').hide();
            } else if (user_type == 3) {
                $('.partnership_type_div').show();
                $('.assign_warehouse_content').hide();
            } else if (user_type == 4) {
                $('.partnership_type_div').hide();
                $('.assign_warehouse_content').hide();
            } else {
                $('.partnership_type_div').hide();
                $('.assign_warehouse_content').show();
            }
        }

        var loopingCount = '{{ !empty($data) && count($data) > 0 ? count($data) : 1 }}';
        $('.partnership_store_add').on('click', function() {
            var appendData = '<div class="form-group repeater-form'+loopingCount+'">' +
                '<div data-repeater-list="partnership_store" class="d-flex flex-column gap-3">' +
                '<div data-repeater-item="" class="form-group d-flex flex-wrap align-items-center gap-5">' +
                '<div class="row" data-repeater-item data-repeater-list="add_partnership_stores">' +
                // '<input type="hidden" name="partnership_store[' + loopingCount + '][id]" value="" class="form-control form-control-sm">' +
                '<div class="col-md-3 mb-5 partnership_type_div">' +
                '<label for="partnership_type_id" class="form-label">{{ __('Partnership Type') }}</label>' +

                '<div class="input-group input-group-sm flex-nowrap">' +
                '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                '<div class="overflow-hidden flex-grow-1">' +
                '<select name="partnership_store[' + loopingCount + '][partnership_type_id]" aria-label="{{ __('Select Partnership Type') }}" data-control="select2" data-placeholder="{{ __('Select Partnership Type..') }}" class="form-select form-select-sm form-select-solid partnership-type-dropdown" data-allow-clear="true" data-form-repeater="select2">' +
                '<option value="">{{ __('Select Partnership Type..') }}</option>' +
                @foreach ($partnership_types as $key => $partnership_type)
                    '<option data-kt-flag="{{ $partnership_type->id }}" value="{{ $partnership_type->id }}" {{ $partnership_type->id == old('partnership_type_id') ? 'selected' : '' }}>{{ $partnership_type->partnership_name }} - {{ $partnership_type->partnership_percentage }}</option>' +
                @endforeach
            '</select>' +
            '</div>' +
            '</div>' +
            @if ($errors->has('partnership_type_id'))
                '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                '<strong>{{ $errors->first('partnership_type_id') }}</strong>' +
                '</span>' +
            @endif
            '</div>' +

            '<div class="col-md-3 mb-5">' +
            '<label for="store_id" class="form-label">{{ __('Store') }}</label>' +
            '<div class="input-group input-group-sm flex-nowrap">' +
            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
            '<div class="overflow-hidden flex-grow-1">' +
            '<select name="partnership_store[' + loopingCount + '][store_id]" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true" data-form-repeater="select2">' +
                '<option value="">{{ __('Select Store..') }}</option>' +
                @foreach ($stores as $key => $stores)
                    '<option data-kt-flag="{{ $stores->id }}" value="{{ $stores->id }}" {{ $stores->id == old('store_id') ? 'selected' : '' }}>{{ $stores->store_code }} - {{ $stores->store_name }}</option>' +
                @endforeach
            '</select>' +
            '</div>' +
            '</div>' +
            @if ($errors->has('store_id'))
                '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                '<strong>{{ $errors->first('store_id') }}</strong>' +
                '</span>' +
            @endif
            '</div>' +

            '<div class="col-md-2 mb-5">' +
            '<label for="status" class="form-label required">{{ __('Status') }}</label>' +

            '<div class="input-group input-group-sm flex-nowrap">' +
            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
            '<div class="overflow-hidden flex-grow-1">' +
            '<select name="partnership_store[' + loopingCount + '][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true" data-form-repeater="select2">' +
                '<option value="">{{ __('Select Status..') }}</option>' +
                @foreach (config('app.statusinactive') as $key => $value)
                    '<option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>' +
                @endforeach
            '</select>' +
            '</div>' +
            '</div>' +
            @if ($errors->has('status'))
                '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                '<strong>{{ $errors->first('status') }}</strong>' +
                '</span>' +
            @endif
            '</div>' +

            '<div class="col-md-3 mb-5">' +
            '<label for="joined_at" class="form-label">{{ __('Joined Date') }}</label>' +
            '<div class="input-group">' +
            '<input id="joined_at" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" name="partnership_store[' + loopingCount + '][joined_at]" placeholder="Enter Joined Date" value="{{ old('joined_at') }}"/>' +
                '<span class="input-group-text border-0">' +
                '<i class="fas fa-calendar"></i>' +
                '</span>' +
                '</div>' +
                @if ($errors->has('joined_at'))
                    '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                    '<strong>{{ $errors->first('joined_at') }}</strong>' +
                    '</span>' +
                @endif
            '</div>' +

            '<div class="col-md-3 mb-5">' +
                '<label for="remarks" class="form-label">{{ __('Remarks') }} </label>' +
                '<input name="partnership_store['+loopingCount+'][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Joined Date" value="{{ old('remarks') }}"/>' +
                @if ($errors->has('remarks'))
                    '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                        '<strong>{{ $errors->first('remarks') }}</strong>' +
                    '</span>' +
                @endif
            '</div>' +

            '<div class="col-md-1 mb-5">' +
            '<button type="button" data-repeater-delete='+loopingCount+' class="btn btn-sm btn-icon btn-light-danger mt-8 store_assign_delete">' +
            '<i class="fa fa-close"></i>' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

            $('.appendBodyData').append(appendData);
            $('.select2-container').remove();
            $('.form-select').select2();
            $(".fsh_flat_datepicker").flatpickr();

            var user_type = $('#usertype-dropdown').find(':selected').val();
            showpartnertype(user_type);

            loopingCount++;
        });

        var loopingCount = '{{ !empty($warehousedata) && count($warehousedata) > 0 ? count($warehousedata) : 1 }}';
        $('.assign_warehouse_add').on('click', function() {
            var appendWarehouseData = '<div class="form-group repeater-form'+loopingCount+'">' +
                '<div data-repeater-list="warehouse_assign" class="d-flex flex-column gap-3">' +
                '<div data-repeater-item="" class="form-group d-flex flex-wrap align-items-center gap-5">' +
                '<div class="row" data-repeater-item data-repeater-list="add_warehouse_assigns">' +
            '<div class="col-md-3 mb-5">' +
            '<label for="warehouse_id" class="form-label">{{ __('Warehouse') }}</label>' +
            '<div class="input-group input-group-sm flex-nowrap">' +
            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
            '<div class="overflow-hidden flex-grow-1">' +
            '<select name="warehouse_assign[' + loopingCount + '][warehouse_id]" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true" data-form-repeater="select2">' +
                '<option value="">{{ __('Select Warehouse..') }}</option>' +
                @foreach($warehouses as $key => $warehouse)
                    '<option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('warehouse_id') ? 'selected' :'' }}>{{ $warehouse->code }} - {{ $warehouse->name }}</option>' +
                @endforeach
            '</select>' +
            '</div>' +
            '</div>' +
            @if ($errors->has('warehouse_id'))
                '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                '<strong>{{ $errors->first('warehouse_id') }}</strong>' +
                '</span>' +
            @endif
            '</div>' +

            '<div class="col-md-2 mb-5">' +
            '<label for="status" class="form-label required">{{ __('Status') }}</label>' +

            '<div class="input-group input-group-sm flex-nowrap">' +
            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
            '<div class="overflow-hidden flex-grow-1">' +
            '<select name="warehouse_assign[' + loopingCount + '][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true" data-form-repeater="select2">' +
                '<option value="">{{ __('Select Status..') }}</option>' +
                @foreach (config('app.statusinactive') as $key => $value)
                    '<option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>' +
                @endforeach
            '</select>' +
            '</div>' +
            '</div>' +
            @if ($errors->has('status'))
                '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                '<strong>{{ $errors->first('status') }}</strong>' +
                '</span>' +
            @endif
            '</div>' +

            '<div class="col-md-3 mb-5">' +
            '<label for="joined_at" class="form-label">{{ __('Joined Date') }}</label>' +
            '<div class="input-group">' +
            '<input id="joined_at" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" name="warehouse_assign[' + loopingCount + '][joined_at]" placeholder="Enter Joined Date" value="{{ old('joined_at') }}"/>' +
                '<span class="input-group-text border-0">' +
                '<i class="fas fa-calendar"></i>' +
                '</span>' +
                '</div>' +
                @if ($errors->has('joined_at'))
                    '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                    '<strong>{{ $errors->first('joined_at') }}</strong>' +
                    '</span>' +
                @endif
            '</div>' +

            '<div class="col-md-3 mb-5">' +
                '<label for="remarks" class="form-label">{{ __('Remarks') }} </label>' +
                '<input name="warehouse_assign['+loopingCount+'][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remarks" value="{{ old('remarks') }}"/>' +
                @if ($errors->has('remarks'))
                    '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                        '<strong>{{ $errors->first('remarks') }}</strong>' +
                    '</span>' +
                @endif
            '</div>' +

            '<div class="col-md-1 mb-5">' +
            '<button type="button" data-repeater-delete='+loopingCount+' class="btn btn-sm btn-icon btn-light-danger mt-8 warehouse_assign_delete">' +
            '<i class="fa fa-close"></i>' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

            $('.appendWarehouseBodyData').append(appendWarehouseData);
            $('.select2-container').remove();
            $('.form-select').select2();
            $(".fsh_flat_datepicker").flatpickr();

            var user_type = $('#usertype-dropdown').find(':selected').val();
            showpartnertype(user_type);

            loopingCount++;
        });

        $('body').on('click', '.store_assign_delete', function() {
            $(this).parent().parent().remove();
        })

        $('body').on('click', '.warehouse_assign_delete', function() {
            $(this).parent().parent().remove();
        })
    })
</script>
