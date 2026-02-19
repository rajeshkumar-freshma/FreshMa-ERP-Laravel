<!--begin::Tab pane-->
<div class="tab-pane fade" id="add_commission_percentage" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <!--begin::Meta options-->
        <div class="card card-flush py-4">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="mb-5">
                    <label for="vendor_commission" class="form-label">{{ __('Commission Percentage') }}</label>
                    <input type="text" name="vendor_commission" value="{{ old('vendor_commission', @$data->vendor_commission) }}" class="form-control form-control-solid" id="vendor_commission" placeholder="Enter vendor_commission Name" min="0" max="100" step="any"/>
                    @if ($errors->has('vendor_commission'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('vendor_commission') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-5">
                    <label for="it_can_edit_on_billing" class="form-label">{{ __('Edit On Billing') }} <br>
                        <input type="checkbox" name="it_can_edit_on_billing" id="it_can_edit_on_billing" class="form-check-input" value="1" {{ old('it_can_edit_on_billing', @$data->it_can_edit_on_billing) == 1 ? "checked" : "" }}>
                    </label>
                    @if ($errors->has('it_can_edit_on_billing'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('it_can_edit_on_billing') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-5">
                    <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                    <textarea name="remarks" class="form-control form-control-solid" rows="5" id="remarks kt_docs_ckeditor_classic" placeholder="Enter remarks">{{ old('remarks', @$data->remarks) }}</textarea>
                    @if ($errors->has('remarks'))
                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>
                
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::Meta options-->
    </div>
</div>
<!--end::Tab pane-->