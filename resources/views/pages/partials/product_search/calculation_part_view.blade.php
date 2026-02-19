<div class="row mb-3">
    <div class="col-md-6">
        <!--begin::Product Search-->
        <div class="row mb-6">
            <!--begin::Label-->
            <div class="col-md-12">
                <div class="mb-5">
                    <label for="status" class="form-label">{{ __('Remarks') }}</label>
                    <P>{{ old('remarks', $calculation_data->remarks) }}</P>
                </div>
            </div>
            <!--begin::Label-->
        </div>
        <!--end::Input group-->
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
                    <h3>{{ old('total_request_quantity', isset($calculation_data->total_request_quantity) ? $calculation_data->total_request_quantity : 0) }}</h3>
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
                    <h3>{{ old('total_given_quantity', isset($calculation_data->total_given_quantity) ? $calculation_data->total_given_quantity : 0) }}</h3>
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
                    <h3>{{ old('total_tax', isset($calculation_data->total_tax) ? $calculation_data->total_tax : 0) }}</h3>
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
                    <h3>{{ old('discount_amount', isset($calculation_data->discount_amount) ? $calculation_data->discount_amount : 0) }}</h3>
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
                    <h3>{{ old('total_expense_amount', isset($calculation_data->total_expense_amount) ? $calculation_data->total_expense_amount : 0) }}</h3>
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
                    <h3>{{ old('total_commission_amount', isset($calculation_data->total_commission_amount) ? $calculation_data->total_commission_amount : 0) }}</h3>
                    <input type="hidden" name="total_commission_amount" class="form-control form-control-sm" id="total_commission_amount_val" value="{{ old('total_commission_amount', isset($calculation_data->total_commission_amount) ? $calculation_data->total_commission_amount : 0) }}">
                </div>
                <!--end::Row-->
            </div>
            <!--end::Col-->
        </div>
        @endif
        @endif

        @if (Request::is('*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/sales-return', '*/sales-return/*', '*/purchase-return', '*/purchase-return/*'))
        <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Sub Total') }}</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row mt-4">
                    <h3>{{ old('sub_total_amount', isset($calculation_data->sub_total) ? $calculation_data->sub_total : 0) }}</h3>
                    <input type="hidden" name="sub_total_amount" class="form-control form-control-sm" id="sub_total_amount_val" value="{{ old('sub_total_amount', isset($calculation_data->sub_total) ? $calculation_data->sub_total : 0) }}">
                </div>
                <!--end::Row-->
            </div>
            <!--end::Col-->
        </div>
        @endif

        @if (Request::is('*/customer-indent-request', '*/customer-indent-request/*', '*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/order-request', '*/order-request/*', '*/customer-sales', '*/customer-sales/*','*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/sales-return', '*/sales-return/*', '*/purchase-return', '*/purchase-return/*'))
        <divsales-returnrow mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Amount') }}</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row mt-4">
                    <h3>{{ old('total_amount', isset($calculation_data->total_amount) ? $calculation_data->total_amount : 0) }}</h3>
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
                    <h3>{{ old('total_amount', isset($calculation_data->total) ? $calculation_data->total : 0) }}</h3>
                    <input type="hidden" name="total_amount" class="form-control form-control-sm" id="total_amount_val" value="{{ old('total_amount', isset($calculation_data->total) ? $calculation_data->total : 0) }}">
                </div>
                <!--end::Row-->
            </div>
            <!--end::Col-->
        </div>
        @endif
    </div>
</div>
