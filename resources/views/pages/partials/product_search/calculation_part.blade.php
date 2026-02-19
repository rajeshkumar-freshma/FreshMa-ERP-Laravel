<div class="row mb-3">
    <div class="col-md-6">
        {{-- @if (Request::is('*/order-request', '*/order-request/*'))
            <div class="mb-5">
                <label for="transport_type" class="required form-label">{{ __('Transport Type') }}</label>

                <div class="input-group input-group-sm flex-nowrap">
                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                    <div class="overflow-hidden flex-grow-1">
                        <select name="transport_type_id" id="transport_type" aria-label="{{ __('Select Transport Type') }}" data-control="select2" data-placeholder="{{ __('Select Transport Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                            <option value="">{{ __('Select Transport Type..') }}</option>
                            @foreach ($transport_types as $key => $transport_type)
                                <option data-kt-flag="{{ $transport_type->id }}" value="{{ $transport_type->id }}" {{ $transport_type->id == old('transport_type_id') ? 'selected' : '' }}>{{ $transport_type->transport_type }}</option>
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
        @endif --}}
    </div>

    <div class="col-md-6 bg-light rounded">
        <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Quantity') }}</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row mt-4">
                    <h3 class="total_request_quantity">{{ old('total_request_quantity', isset($calculation_data->total_request_quantity) ? $calculation_data->total_request_quantity : 0) }}</h3>
                    <input type="hidden" name="total_request_quantity" class="form-control form-control-sm" id="total_request_quantity_val" value="{{ old('total_request_quantity', isset($calculation_data->total_request_quantity) ? $calculation_data->total_request_quantity : 0) }}">
                </div>
                <!--end::Row-->
            </div>
            <!--end::Col-->
        </div>

        @if (Request::is('*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Given Quantity') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="total_given_quantity">{{ old('total_given_quantity', isset($calculation_data->total_given_quantity) ? $calculation_data->total_given_quantity : 0) }}</h3>
                        <input type="hidden" name="total_given_quantity" class="form-control form-control-sm" id="total_given_quantity_val" value="{{ old('total_given_quantity', isset($calculation_data->total_given_quantity) ? $calculation_data->total_given_quantity : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
        @endif

        @if (Request::is('*/purchase-order', '*/purchase-order/*','*/purchase-credit', '*/purchase-credit/*'))
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Tax') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="total_tax">{{ old('total_tax', isset($calculation_data->total_tax) ? $calculation_data->total_tax : 0) }}</h3>
                        <input type="hidden" name="total_tax" class="form-control form-control-sm" id="total_tax_val" value="{{ old('total_tax', isset($calculation_data->total_tax) ? $calculation_data->total_tax : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>

            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Discount') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="total_discount">{{ old('discount_amount', isset($calculation_data->discount_amount) ? $calculation_data->discount_amount : 0) }}</h3>
                        <input type="hidden" name="discount_amount" class="form-control form-control-sm" id="total_discount_val" value="{{ old('discount_amount', isset($calculation_data->discount_amount) ? $calculation_data->discount_amount : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
        @endif

        @if (Request::is('*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/purchase-order', '*/purchase-order/*','*/purchase-credit', '*/purchase-credit/*'))
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Expense') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="total_expense_amount">{{ old('total_expense_amount', isset($calculation_data->total_expense_amount) ? $calculation_data->total_expense_amount : 0) }}</h3>
                        <input type="hidden" name="total_expense_amount" class="form-control form-control-sm" id="total_expense_amount_val" value="{{ old('total_expense_amount', isset($calculation_data->total_expense_amount) ? $calculation_data->total_expense_amount : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>

            @if (Request::is('*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Commission') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row mt-4">
                            <h3 class="total_commission_amount">{{ old('total_commission_amount', isset($calculation_data->total_commission_amount) ? $calculation_data->total_commission_amount : 0) }}</h3>
                            <input type="hidden" name="total_commission_amount" class="form-control form-control-sm" id="total_commission_amount_val" value="{{ old('total_commission_amount', isset($calculation_data->total_commission_amount) ? $calculation_data->total_commission_amount : 0) }}">
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
            @endif
        @endif

        @if (Request::is('*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/purchase-return', '*/purchase-return/*'))
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Sub Total') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="sub_total_amount">{{ old('sub_total_amount', isset($calculation_data->sub_total) ? $calculation_data->sub_total : 0) }}</h3>
                        <input type="hidden" name="sub_total_amount" class="form-control form-control-sm" id="sub_total_amount_val" value="{{ old('sub_total_amount', isset($calculation_data->sub_total) ? $calculation_data->sub_total : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
        @endif

        @if (Request::is('*/customer-indent-request', '*/customer-indent-request/*', '*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/order-request', '*/order-request/*', '*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/purchase-return', '*/purchase-return/*'))
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Amount') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="total_amount">{{ old('total_amount', isset($calculation_data->total_amount) ? $calculation_data->total_amount : 0) }}</h3>
                        <input type="hidden" name="total_amount" class="form-control form-control-sm" id="total_amount_val" value="{{ old('total_amount', isset($calculation_data->total_amount) ? $calculation_data->total_amount : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
        @endif

        @if (Request::is('*/purchase-order', '*/purchase-order/*','*/purchase-credit', '*/purchase-credit/*'))
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Amount') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row mt-4">
                        <h3 class="total_amount">{{ old('total_amount', isset($calculation_data->total) ? $calculation_data->total : 0) }}</h3>
                        <input type="hidden" name="total_amount" class="form-control form-control-sm" id="total_amount_val" value="{{ old('total_amount', isset($calculation_data->total) ? $calculation_data->total : 0) }}">
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
        @endif
    </div>
</div>
