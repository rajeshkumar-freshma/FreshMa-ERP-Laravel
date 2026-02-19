<script>
    $(function() {
        var loopingCount = $('.employee_store_row').length || 1;
        $('.employee_store_add').on('click', function() {
            var appendData = '<div class="form-group employee_store_row repeater-form' + loopingCount + '">' +
                '<div data-repeater-list="employee_store" class="flex-column gap-3">' +
                '<div data-repeater-item="" class="form-group flex-wrap align-items-center gap-5">' +
                '<div class="row" data-repeater-item data-repeater-list="add_employee_stores">' +
                '<div class="col-md-3 mb-5">' +
                '<label for="store_id" class="form-label">{{ __('Store') }}</label>' +
                '<div class="input-group input-group-sm flex-nowrap">' +
                '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                '<div class="overflow-hidden flex-grow-1">' +
                '<select name="employee_store[' + loopingCount + '][store_id]" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true" data-form-repeater="select2">' +
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

            '<div class="col-md-3 mb-5">' +
                '<label for="department_id" class="form-label">{{ __('Department') }}</label>' +

                '<div class="input-group input-group-sm flex-nowrap">' +
                    '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                    '<div class="overflow-hidden flex-grow-1">' +
                        '<select name="employee_store[' + loopingCount + '][department_id]" aria-label="{{ __('Select Department') }}" data-control="select2" data-placeholder="{{ __('Select Department..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">' +
                            '<option value="">{{ __('Select Department..') }}</option>' +
                            @foreach ($departments as $keys => $department)
                                '<option data-kt-flag="{{ $department->id }}" value="{{ $department->id }}" {{ $department->id == old('department_id', isset($data[$key]) ? @$data[$key]['department_id'] : @$data['department_id'][$key]) ? 'selected' : '' }}>{{ $department->name }}</option>' +
                            @endforeach
                        '</select>' +
                    '</div>' +
                '</div>' +
            '</div>' +

            '<div class="col-md-3 mb-5">' +
                '<label for="designation_id" class="form-label">{{ __('Designation') }}</label>' +

                '<div class="input-group input-group-sm flex-nowrap">' +
                    '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                    '<div class="overflow-hidden flex-grow-1">' +
                        '<select name="employee_store[' + loopingCount + '][designation_id]" aria-label="{{ __('Select Designation') }}" data-control="select2" data-placeholder="{{ __('Select Designation..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">' +
                            '<option value="">{{ __('Select Designation..') }}</option>' +
                            @foreach ($designations as $keys => $designation)
                                '<option data-kt-flag="{{ $designation->id }}" value="{{ $designation->id }}" {{ $designation->id == old('designation_id', isset($data[$key]) ? @$data[$key]['designation_id'] : @$data['designation_id'][$key]) ? 'selected' : '' }}>{{ $designation->name }}</option>' +
                            @endforeach
                        '</select>' +
                    '</div>' +
                '</div>' +
            '</div>' +

            '<div class="col-md-2 mb-5">' +
            '<label for="status" class="form-label required">{{ __('Status') }}</label>' +

            '<div class="input-group input-group-sm flex-nowrap">' +
            '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
            '<div class="overflow-hidden flex-grow-1">' +
            '<select name="employee_store[' + loopingCount + '][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true" data-form-repeater="select2">' +
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
            '<input id="joined_at" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" name="employee_store[' + loopingCount + '][joined_at]" placeholder="Enter Joined Date" value="{{ old('joined_at') }}"/>' +
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
            '<input name="employee_store[' + loopingCount + '][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Joined Date" value="{{ old('remarks') }}"/>' +
                @if ($errors->has('remarks'))
                    '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                    '<strong>{{ $errors->first('remarks') }}</strong>' +
                    '</span>' +
                @endif
            '</div>' +

            '<div class="col-md-1 mb-5">' +
            '<button type="button" data-repeater-delete=' + loopingCount + ' class="btn btn-sm btn-icon btn-light-danger mt-8 store_assign_delete">' +
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

            loopingCount++;
        });

        $('body').on('click', '.store_assign_delete', function() {
            $(this).parent().parent().remove();
        })
    })
</script>
