<div class="card mt-2">
    <!--begin::Card header-->
    @include('pages.partials.form_collapse_header', ['header_name' => 'Transport Details', 'card_key' => 'transport_details'])
    <!--begin::Card header-->

    <div id="transport_details" class="collapse">
        <!--begin::Card body-->
        <div class="card-body border-top px-9 py-4 add_more_transport_tracking_data">
            <!--begin::Input group-->
            @if ($is_old_data == 'old_value')
                @foreach ($transport_detail['transport_type_id'] as $key => $item)
                    <div class="row mb-3 add_more_transport_tracking_data append_transport_tracking_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" name="transport_tracking[transport_tracking_id][]" value="{{ @$item->id }}" id="transport_tracking_id" class="form-control form-control-sm">
                            <div class="mb-5">
                                <label for="transport_type" class="form-label">{{ __('Transport Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transport_tracking[transport_type_id][]" id="transport_type" aria-label="{{ __('Select Transport Type') }}" data-control="select2" data-placeholder="{{ __('Select Transport Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Transport Type..') }}</option>
                                            @foreach ($transport_types as $transport_type)
                                                <option data-kt-flag="{{ $transport_type->id }}" value="{{ $transport_type->id }}" {{ $transport_type->id == old('transport_type_id', @$item->transport_type_id) ? 'selected' : '' }}> {{ $transport_type->transport_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('transport_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transport_name" class=" form-label">{{ __('Transport Name') }}</label>
                                <input id="transport_name" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_name][]" value="{{ old('transport_name', @$item->transport_name) }}" />
                                @if ($errors->has('transport_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transport_number" class=" form-label">{{ __('Transport Number') }}</label>
                                <input id="transport_number" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_number][]" value="{{ old('transport_number', @$item->transport_number) }}" />
                                @if ($errors->has('transport_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="departure_datetime" class=" form-label">{{ __('Departure Dates') }}</label>
                                <input id="departure_datetime" type="text" class="form-control form-control-sm form-control-solid flatpickr-calendar" name="transport_tracking[departure_datetime][]" value="{{ old('departure_datetime', @$item->departure_datetime) }}" />
                                @if ($errors->has('departure_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('departure_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="arriving_datetime" class=" form-label">{{ __('Arriving Date') }}</label>
                                <input id="arriving_datetime" type="text" class="form-control form-control-sm form-control-solid flatpickr-calendar" name="transport_tracking[arriving_datetime][]" value="{{ old('arriving_datetime', @$item->arriving_datetime) }}" />
                                @if ($errors->has('arriving_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('arriving_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="attachments" class=" form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view(@$item->image_full_url) !!}</label>
                                <input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_tracking_file][]" />
                                @if ($errors->has('transport_tracking_file'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_tracking_file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="from_location" class=" form-label">{{ __('From Location') }}</label>
                                <input id="from_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[from_location][]" value="{{ old('from_location', @$item->from_location) }}" />
                                @if ($errors->has('from_location'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label">{{ __('To Location') }}</label>
                                <input id="to_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[to_location][]" value="{{ old('to_location', @$item->to_location) }}" />
                                @if ($errors->has('to_location'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label"> &nbsp;</label>
                                <button type="button" class="btn btn-sm btn-primary add_transport_tracking_data mt-7"><i class="fa fa-plus"></i></button>
                                @if ($key != 0)
                                    <button type="button" class="btn btn-sm btn-danger remove_transport_tracking_data mt-7" data-loop={{ $key }}><i class="fa fa-close"></i></button>
                                @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                @endforeach
            @elseif ($is_old_data == 'edit_value' && count($transport_detail) > 0)
                @foreach ($transport_detail as $key => $item)
                    <div class="row mb-3 add_more_transport_tracking_data append_transport_tracking_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" name="transport_tracking[transport_tracking_id][]" value="{{ @$item->id }}" id="transport_tracking_id" class="form-control form-control-sm">
                            <div class="mb-5">
                                <label for="transport_type" class="form-label">{{ __('Transport Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="transport_tracking[transport_type_id][]" id="transport_type" aria-label="{{ __('Select Transport Type') }}" data-control="select2" data-placeholder="{{ __('Select Transport Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Transport Type..') }}</option>
                                            @foreach ($transport_types as $transport_type)
                                                <option data-kt-flag="{{ $transport_type->id }}" value="{{ $transport_type->id }}" {{ $transport_type->id == old('transport_type_id', @$item->transport_type_id) ? 'selected' : '' }}> {{ $transport_type->transport_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('transport_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transport_name" class=" form-label">{{ __('Transport Name') }}</label>
                                <input id="transport_name" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_name][]" value="{{ old('transport_name', @$item->transport_name) }}" />
                                @if ($errors->has('transport_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transport_number" class=" form-label">{{ __('Transport Number') }}</label>
                                <input id="transport_number" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_number][]" value="{{ old('transport_number', @$item->transport_number) }}" />
                                @if ($errors->has('transport_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="departure_datetime" class=" form-label">{{ __('Departure Dates') }}</label>
                                <input id="departure_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="transport_tracking[departure_datetime][]" value="{{ old('departure_datetime', @$item->departure_datetime) }}" />
                                @if ($errors->has('departure_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('departure_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="arriving_datetime" class=" form-label">{{ __('Arriving Date') }}</label>
                                <input id="arriving_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="transport_tracking[arriving_datetime][]" value="{{ old('arriving_datetime', @$item->arriving_datetime) }}" />
                                @if ($errors->has('arriving_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('arriving_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="attachments" class=" form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view(@$item->image_full_url) !!}</label>
                                <input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_tracking_file][]" />
                                @if ($errors->has('transport_tracking_file'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transport_tracking_file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="from_location" class=" form-label">{{ __('From Location') }}</label>
                                <input id="from_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[from_location][]" value="{{ old('from_location', @$item->from_location) }}" />
                                @if ($errors->has('from_location'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label">{{ __('To Location') }}</label>
                                <input id="to_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[to_location][]" value="{{ old('to_location', @$item->to_location) }}" />
                                @if ($errors->has('to_location'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label"> &nbsp;</label>
                                <button type="button" class="btn btn-sm btn-primary add_transport_tracking_data mt-7"><i class="fa fa-plus"></i></button>
                                @if ($key != 0)
                                    <button type="button" class="btn btn-sm btn-danger remove_transport_tracking_data mt-7" data-loop={{ $key }}><i class="fa fa-close"></i></button>
                                @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                @endforeach
            @else
                <div class="row mb-3 add_more_transport_tracking_data append_transport_tracking_data0">
                    <!--begin::Label-->
                    <div class="col-md-4">
                        <input type="hidden" name="transport_tracking[transport_tracking_id][]" value="{{ @$item->id }}" id="transport_tracking_id" class="form-control form-control-sm">
                        <div class="mb-5">
                            <label for="transport_type" class="form-label">{{ __('Transport Type') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="transport_tracking[transport_type_id][]" id="transport_type" aria-label="{{ __('Select Transport Type') }}" data-control="select2" data-placeholder="{{ __('Select Transport Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Transport Type..') }}</option>
                                        @foreach ($transport_types as $transport_type)
                                            <option data-kt-flag="{{ $transport_type->id }}" value="{{ $transport_type->id }}" {{ $transport_type->id == old('transport_type_id', @$item->transport_type_id) ? 'selected' : '' }}> {{ $transport_type->transport_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('transport_type_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('transport_type_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transport_name" class=" form-label">{{ __('Transport Name') }}</label>
                            <input id="transport_name" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_name][]" value="{{ old('transport_name', @$item->transport_name) }}" />
                            @if ($errors->has('transport_name'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('transport_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transport_number" class=" form-label">{{ __('Transport Number') }}</label>
                            <input id="transport_number" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_number][]" value="{{ old('transport_number', @$item->transport_number) }}" />
                            @if ($errors->has('transport_number'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('transport_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="departure_datetime" class=" form-label">{{ __('Departure Date') }}</label>
                            <input id="departure_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="transport_tracking[departure_datetime][]" value="{{ old('departure_datetime', @$item->departure_datetime) }}" />
                            @if ($errors->has('departure_datetime'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('departure_datetime') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="arriving_datetime" class=" form-label">{{ __('Arriving Date') }}</label>
                            <input id="arriving_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="transport_tracking[arriving_datetime][]" value="{{ old('arriving_datetime', @$item->arriving_datetime) }}" />
                            @if ($errors->has('arriving_datetime'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('arriving_datetime') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="attachments" class=" form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view(@$item->image_full_url) !!}</label>
                            <input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="transport_tracking[transport_tracking_file][]" />
                            @if ($errors->has('transport_tracking_file'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('transport_tracking_file') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_location" class=" form-label">{{ __('From Location') }}</label>
                            <input id="from_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[from_location][]" value="{{ old('from_location', @$item->from_location) }}" />
                            @if ($errors->has('from_location'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('from_location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_location" class=" form-label">{{ __('To Location') }}</label>
                            <input id="to_location" type="text" class="form-control form-control-sm form-control-solid" name="transport_tracking[to_location][]" value="{{ old('to_location', @$item->to_location) }}" />
                            @if ($errors->has('to_location'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('to_location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_location" class=" form-label"> &nbsp;</label>
                            <button type="button" class="btn btn-sm btn-primary add_transport_tracking_data mt-7"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="append_transport_tracking_data">
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
</div>
