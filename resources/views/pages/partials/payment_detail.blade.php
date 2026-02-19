<div class="card mt-2">
    <!--begin::Card header-->
    @include('pages.partials.form_collapse_header', ['header_name' => 'Payment Details', 'card_key' => 'payment_details'])
    <!--begin::Card header-->

    <div id="payment_details" class="collapse">
        <!--begin::Card body-->
        <div class="card-body border-top px-9 py-4 add_more_payment_data">
            <!--begin::Input group-->
            @if ($is_old_data == 'old_value')
                @foreach ($payment_details['payment_type_id'] as $key => $item)
                {{-- <p>{{ print_r($payment_details['payment_type_id'][$key]) }}</p> --}}
                    <div class="row mb-3 add_more_payment_data append_payment_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" name="payment_details[payment_id][]" value="{{ @$item->id }}" id="payment_id" class="form-control form-control-sm">
                            <div class="mb-5">
                                <label for="payment_type" class="form-label">{{ __('Payment Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="payment_details[payment_type_id][]" id="payment_type" aria-label="{{ __('Select Payment Type') }}" data-control="select2" data-placeholder="{{ __('Select Payment Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Payment Type..') }}</option>
                                            @foreach ($payment_types as $payment_type)
                                                <option data-kt-flag="{{ $payment_type->id }}" value="{{ $payment_type->id }}" {{ $payment_type->id == old('payment_type_id', @$payment_details['payment_type_id'][$key]) ? 'selected' : '' }}> {{ $payment_type->payment_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('payment_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transaction_datetime" class=" form-label">{{ __('Transaction Date') }}</label>
                                <input id="transaction_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="payment_details[transaction_datetime][]" value="{{ old('transaction_datetime', @$payment_details['transaction_datetime'][$key]) }}" />
                                @if ($errors->has('transaction_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transaction_amount" class=" form-label">{{ __('Amount') }}</label>
                                <input id="transaction_amount" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[transaction_amount][]" value="{{ old('transaction_amount', @$payment_details['transaction_amount'][$key]) }}" />
                                @if ($errors->has('payment_details.transaction_amount.'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payment_details.transaction_amount.') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="remark" class=" form-label">{{ __('Remark') }}</label>
                                <textarea id="remark" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[remark][]">{{ old('remark', @$payment_details['remark'][$key]) }}</textarea>
                                @if ($errors->has('remark'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('remark') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="attachments" class=" form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view(@$item->image_full_url) !!}</label>
                                <input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="payment_details[payment_transaction_documents][]" mulitple />
                                @if ($errors->has('payment_transaction_documents'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_transaction_documents') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label"> &nbsp;</label>
                                <button type="button" class="btn btn-sm btn-primary add_payment_data mt-7"><i class="fa fa-plus"></i></button>
                                @if ($key != 0)
                                    <button type="button" class="btn btn-sm btn-danger remove_payment_data mt-7" data-loop={{ $key }}><i class="fa fa-close"></i></button>
                                @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                @endforeach
            @elseif ($is_old_data == 'edit_value' && count($payment_details) > 0)
                @foreach ($payment_details as $key => $item)
                    <div class="row mb-3 add_more_payment_data append_payment_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" name="payment_details[payment_id][]" value="{{ @$item->id }}" id="payment_id" class="form-control form-control-sm">
                            <div class="mb-5">
                                <label for="payment_type" class="form-label">{{ __('Payment Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="payment_details[payment_type_id][]" id="payment_type" aria-label="{{ __('Select Payment Type') }}" data-control="select2" data-placeholder="{{ __('Select Payment Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Payment Type..') }}</option>
                                            @foreach ($payment_types as $payment_type)
                                                <option data-kt-flag="{{ $payment_type->id }}" value="{{ $payment_type->id }}" {{ $payment_type->id == old('payment_type_id', @$item->payment_type_id) ? 'selected' : '' }}> {{ $payment_type->payment_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('payment_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transaction_datetime" class=" form-label">{{ __('Transaction Date') }}</label>
                                <input id="transaction_datetime" type="text" class="form-control form-control-sm form-control-solid datetime_picker" name="payment_details[transaction_datetime][]" value="{{ old('transaction_datetime', @$item->transaction_datetime) }}" />
                                @if ($errors->has('transaction_datetime'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transaction_amount" class=" form-label">{{ __('Amount') }}</label>
                                <input id="transaction_amount" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[transaction_amount][]" value="{{ old('transaction_amount', @$item->amount) }}" />
                                @if ($errors->has('transaction_amount'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="remark" class=" form-label">{{ __('Remark') }}</label>
                                <textarea id="remark" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[remark][]">{{ old('remark', @$item->note) }}</textarea>
                                @if ($errors->has('remark'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('remark') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="attachments" class=" form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view(@$item->payment_transaction_documents[0]->image_full_url) !!}</label>
                                <input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="payment_details[payment_transaction_documents][]" mulitple />
                                @if ($errors->has('payment_transaction_documents'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_transaction_documents') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label"> &nbsp;</label>
                                <button type="button" class="btn btn-sm btn-primary add_payment_data mt-7"><i class="fa fa-plus"></i></button>
                                @if ($key != 0)
                                    <button type="button" class="btn btn-sm btn-danger remove_payment_data mt-7" data-loop={{ $key }}><i class="fa fa-close"></i></button>
                                @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                @endforeach
            @else
                <div class="row mb-3 add_more_payment_data append_payment_data0">
                    <!--begin::Label-->
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="payment_type" class="form-label">{{ __('Payment Type') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="payment_details[payment_type_id][]" id="payment_type" aria-label="{{ __('Select Payment Type') }}" data-control="select2" data-placeholder="{{ __('Select Payment Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Payment Type..') }}</option>
                                        @foreach ($payment_types as $payment_type)
                                            <option data-kt-flag="{{ $payment_type->id }}" value="{{ $payment_type->id }}" {{ $payment_type->id == old('payment_type_id', @$item->payment_type_id) ? 'selected' : '' }}> {{ $payment_type->payment_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('payment_type_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payment_type_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transaction_datetime" class=" form-label">{{ __('Transaction Date') }}</label>
                            <input id="transaction_datetime" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="payment_details[transaction_datetime][]" value="{{ old('transaction_datetime', @$item->transaction_datetime) }}" />
                            @if ($errors->has('transaction_datetime'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('transaction_datetime') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="transaction_amount" class=" form-label">{{ __('Amount') }}</label>
                            <input id="transaction_amount" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[transaction_amount][]" value="{{ old('transaction_amount', @$item->transaction_amount) }}" />
                            @if ($errors->has('transaction_amount'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('transaction_amount') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="remark" class=" form-label">{{ __('Remark') }}</label>
                            <textarea id="remark" type="text" class="form-control form-control-sm form-control-solid" name="payment_details[remark][]">{{ old('remark', @$item->remark) }}</textarea>
                            @if ($errors->has('remark'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('remark') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="attachments" class=" form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view(@$item->image_full_url) !!}</label>
                            <input id="attachments" type="file" class="form-control form-control-sm form-control-solid" name="payment_details[payment_transaction_documents][]" mulitple />
                            @if ($errors->has('payment_transaction_documents'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payment_transaction_documents') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-5">
                            <label for="to_location" class=" form-label"> &nbsp;</label>
                            <button type="button" class="btn btn-sm btn-primary add_payment_data mt-7"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="append_payment_data">
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
</div>
