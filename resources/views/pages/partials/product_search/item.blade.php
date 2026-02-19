<div class="row mb-6">
    <div class="table-responsive">
        <table class="table table-sm table-rounded table-striped border gy-4 gs-7">
            <thead class="table-light">
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200 fw-bolder">
                    @if (Request::is(
                            '*/purchase-order',
                            '*/purchase-order/*',
                            '*/customer-sales',
                            '*/customer-sales/*',
                            '*/sales-order',
                            '*/sales-order/*',
                            '*/store-sales',
                            '*/store-sales/*',
                            '*/product-transfer',
                            '*/product-transfer/*'))
                        <th style="width: 50px;">
                            <label for="expense_billable_all_checkbox">Is Billable</label>
                            <input type="checkbox" class="form-check-input expense_billable_all_checkbox"
                                id="expense_billable_all_checkbox" name="is_inc_exp_billable_for_all" value="1"
                                {{ old('is_inc_exp_billable_for_all', @$main_data->is_inc_exp_billable_for_all) == 1 ? 'checked' : '' }}>
                        </th>
                    @endif

                    <th style="width: 400px;">{{ __('Product Name') }}</th>

                    @if (Request::is(
                            '*/store-indent-request',
                            '*/store-indent-request/*',
                            '*/customer-indent-request',
                            '*/customer-indent-request/*',
                            '*/warehouse-indent-request',
                            '*/warehouse-indent-request/*',
                            '*/purchase-order',
                            '*/purchase-order/*',
                            '*/order-request',
                            '*/order-request/*',
                            '*/customer-sales',
                            '*/customer-sales/*',
                            '*/sales-order',
                            '*/sales-order/*',
                            '*/store-sales',
                            '*/store-sales/*',
                            '*/sales-return',
                            '*/sales-return/*',
                            '*/purchase-return',
                            '*/purchase-return/*',
                            '*/product-transfer',
                            '*/product-transfer/*',
                            '*/product-transfer',
                            '*/product-transfer/*'))
                        <th style="width: 150px;">{{ __('Unit') }}</th>
                    @endif

                    @if (Request::is('*/adjustment', '*/adjustment/*'))
                        <th style="width: 150px;">{{ __('Type') }}</th>
                    @endif

                    <th>{{ __('Quantity') }}</th>


                    @if (Request::is('*/adjustment', '*/adjustment/*'))
                        <th style="width: 150px;">{{ __('Remarks') }}</th>
                    @endif

                    @if (Request::is(
                            '*/purchase-order',
                            '*/purchase-order/*',
                            '*/order-request',
                            '*/order-request/*',
                            '*/customer-sales',
                            '*/customer-sales/*',
                            '*/sales-order',
                            '*/sales-order/*',
                            '*/store-sales',
                            '*/store-sales/*'))
                        <th>{{ __('Given Quantity') }}</th>
                    @endif

                    @if (Request::is(
                            '*/customer-indent-request',
                            '*/customer-indent-request/*',
                            '*/warehouse-indent-request',
                            '*/warehouse-indent-request/*',
                            '*/purchase-order',
                            '*/purchase-order/*',
                            '*/order-request',
                            '*/order-request/*',
                            '*/customer-sales',
                            '*/customer-sales/*',
                            '*/sales-order',
                            '*/sales-order/*',
                            '*/store-sales',
                            '*/store-sales/*',
                            '*/sales-return',
                            '*/sales-return/*',
                            '*/purchase-return',
                            '*/purchase-return/*'))
                        <th>{{ __('Amount') }}</th>
                        @if (Request::is(
                                '*/purchase-order',
                                '*/purchase-order/*',
                                '*/customer-sales',
                                '*/customer-sales/*',
                                '*/sales-order',
                                '*/sales-order/*',
                                '*/store-sales',
                                '*/store-sales/*'))
                            <th style="width: 200px;">{{ __('Tax') }}</th>
                            <th>{{ __('Discount Type') }}</th>
                            <th>{{ __('Amount/') }} <br> {{ __('Percentage') }}</th>
                        @endif
                        @if (Request::is(
                                '*/customer-sales',
                                '*/customer-sales/*',
                                '*/sales-order',
                                '*/sales-order/*',
                                '*/store-sales',
                                '*/store-sales/*'))
                            <th>{{ __('Expense Amount') }}</th>
                        @endif

                        <th>{{ __('Sub Total') }}</th>


                        @if (Request::is(
                                '*/customer-sales',
                                '*/customer-sales/*',
                                '*/sales-order',
                                '*/sales-order/*',
                                '*/store-sales',
                                '*/store-sales/*'))
                            <th style="width: 200px;">{{ __('Commission Percentage') }}</th>
                            <th>{{ __('Total') }}</th>
                        @endif
                    @endif
                    {{-- <th id="box_view">{{ __('Box No') }}</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody class="appendData">
                @if (!empty($data) && count($data) > 0)
                    @if ($is_old_data)
                        @foreach ($data['product_id'] as $key => $item)
                            @php
                                $product = App\Models\Product::findOrfail($data['product_id'][$key]);
                            @endphp
                            <tr class="product_row current_row{{ $product->id }}">
                                @if (Request::is(
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*',
                                        '*/sales-return',
                                        '*/sales-return/*',
                                        '*/purchase-return',
                                        '*/purchase-return/*',
                                        '*/product-transfer',
                                        '*/product-transfer/*'))
                                    <td style="width: 50px;">
                                        <input type="checkbox" data-loopkey="{{ $key }}"
                                            class="form-check-input expense_billable_product_checkbox"
                                            id="expense_billable_product_checkbox{{ $key }}"
                                            name="products[is_inc_exp_billable][]" value="1"
                                            {{ @$data['is_inc_exp_billable'][$key] == 1 ? 'checked' : '' }} disabled>
                                        <input type="hidden" data-loopkey="{{ $key }}"
                                            class="form-control form-control-sm inc_exp_amount"
                                            id="inc_exp_amount{{ $key }}" name="products[inc_exp_amount][]"
                                            value="{{ $data['inc_exp_amount'][$key] ?? '' }}">
                                    </td>
                                    @if ($errors->has('products.is_inc_exp_billable.' . $key))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('products.is_inc_exp_billable.' . $key) }}</strong>
                                        </span>
                                    @endif
                                    @if ($errors->has('products.inc_exp_amount.' . $key))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('products.inc_exp_amount.' . $key) }}</strong>
                                        </span>
                                    @endif
                                @endif

                                <td>
                                    <input type="hidden" class="form-control form-control-sm product"
                                        data-loopkey="{{ $key }}" value="{{ $data['id'][$key] }}"
                                        name="products[id][]">
                                    <input type="hidden" class="form-control form-control-sm product"
                                        data-loopkey="{{ $key }}" value="{{ $product->id }}"
                                        name="products[product_id][]" required>
                                    {{ __($product->sku_code != null ? $product->name . ' - ' . $product->sku_code : $product->name) }}
                                </td>

                                @if (Request::is(
                                        '*/store-indent-request',
                                        '*/store-indent-request/*',
                                        '*/customer-indent-request',
                                        '*/customer-indent-request/*',
                                        '*/warehouse-indent-request',
                                        '*/warehouse-indent-request/*',
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/order-request',
                                        '*/order-request/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*',
                                        '*/sales-return',
                                        '*/sales-return/*',
                                        '*/purchase-return',
                                        '*/purchase-return/*'))
                                    <td>
                                        <select name="products[unit_id][]" id="unit_id{{ $key }}"
                                            data-loopkey="{{ $key }}" aria-label="{{ __('Search Unit') }}"
                                            data-control="select2" data-placeholder="{{ __('Search Unit..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Search Unit..') }}</option>
                                            @foreach ($units as $keys => $unit)
                                                <option data-kt-flag="{{ $unit->id }}" value="{{ $unit->id }}"
                                                    {{ $unit->id == old('unit_id', $data['unit_id'][$key] != null ? $data['unit_id'][$key] : $product->unit_id) ? 'selected' : '' }}>
                                                    {{ $unit->unit_short_code }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('products.unit_id.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('products.unit_id.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                @endif

                                @if (Request::is('*/adjustment', '*/adjustment/*'))
                                    <td>
                                        <select name="products[type][]" id="type{{ $key }}"
                                            data-loopkey="{{ $key }}" aria-label="{{ __('Search Type') }}"
                                            data-control="select2" data-placeholder="{{ __('Search Type..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Search Type..') }}</option>
                                            <option value="1"
                                                {{ 1 == old('type', $data['type'][$key] != null ? $data['type'][$key] : '') ? 'selected' : '' }}>
                                                Addition</option>
                                            <option value="2"
                                                {{ 2 == old('type', $data['type'][$key] != null ? $data['type'][$key] : '') ? 'selected' : '' }}>
                                                Subtraction</option>
                                        </select>
                                        @if ($errors->has('products.type.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('products.type.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                @endif

                                @if (Request::is('*/adjustment', '*/adjustment/*'))
                                    <td>
                                        <input type="text" class="form-control form-control-sm quantity"
                                            id="quantity{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[quantity][]" value="{{ $data['quantity'][$key] }}"
                                            style="width: 100px;" required
                                            {{ \Request::is('*/order-request', '*/order-request/*') ? 'readonly' : 'required' }}>
                                        @if ($errors->has('products.quantity.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('products.quantity.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <textarea class="form-control form-control-sm remarks" id="remarks{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[remarks][]">{{ @$data['remarks'][$key] }}</textarea>
                                        @if ($errors->has('products.remarks.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('products.remarks.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                @else
                                    <td>
                                        <input type="text" class="form-control form-control-sm quantity"
                                            id="quantity{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[quantity][]" value="{{ $data['quantity'][$key] }}"
                                            style="width: 100px;"
                                            {{ \Request::is('*/order-request', '*/order-request/*') ? 'readonly' : 'required' }}>
                                        @if ($errors->has('products.quantity.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('products.quantity.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                    
                                @endif

                                @if (Request::is(
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/order-request',
                                        '*/order-request/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*'))
                                    <td>
                                        <input type="text" class="form-control form-control-sm given_quantity"
                                            id="given_quantity{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[given_quantity][]"
                                            value="{{ $data['given_quantity'][$key] }}" style="width: 100px;"
                                            required>
                                        @if ($errors->has('products.given_quantity.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('products.given_quantity.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                @endif

                                @if (Request::is(
                                        '*/customer-indent-request',
                                        '*/customer-indent-request/*',
                                        '*/warehouse-indent-request',
                                        '*/warehouse-indent-request/*',
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/order-request',
                                        '*/order-request/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*',
                                        '*/sales-return',
                                        '*/sales-return/*',
                                        '*/purchase-return',
                                        '*/purchase-return/*'))
                                    <td>
                                        <input type="text" class="form-control form-control-sm amount"
                                            id="amount{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[amount][]" value="{{ $data['amount'][$key] }}"
                                            style="width: 100px;" required>

                                        <span class="d-flex">Per Unit : </span><span
                                            id="per_unit_amount{{ $key }}">{{ $data['per_unit_price'][$key] }}</span>
                                        <input type="hidden" class="form-control form-control-sm per_unit_amount_val"
                                            id="per_unit_amount_val{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[per_unit_price][]"
                                            value="{{ $data['per_unit_price'][$key] }}" style="width: 100px;">
                                        @if ($errors->has('products.amount.' . $key))
                                            <span class="fv-plugins-message-container invalid-feedback"
                                                role="alert">
                                                <strong>{{ $errors->first('products.amount.' . $key) }}</strong>
                                            </span>
                                        @endif
                                    </td>

                                    @if (Request::is(
                                            '*/purchase-order',
                                            '*/purchase-order/*',
                                            '*/customer-sales',
                                            '*/customer-sales/*',
                                            '*/sales-order',
                                            '*/sales-order/*',
                                            '*/store-sales',
                                            '*/store-sales/*'))
                                        <td>
                                            <select name="products[tax_id][]" id="tax_rate{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                aria-label="{{ __('Search Tax') }}" data-control="select2"
                                                data-placeholder="{{ __('Search Tax..') }}"
                                                class="form-select form-select-sm form-select-solid tax_rate"
                                                data-allow-clear="true">
                                                <option value="">{{ __('Search Tax..') }}</option>
                                                @foreach ($tax_rates as $keys => $tax_rate)
                                                    <option data-kt-flag="{{ $tax_rate->id }}"
                                                        data-tax_rate="{{ $tax_rate->tax_rate }}"
                                                        value="{{ $tax_rate->id }}"
                                                        {{ $tax_rate->id == old('tax_id', $data['tax_id'][$key]) ? 'selected' : '' }}>
                                                        {{ $tax_rate->tax_name . ' - ' . $tax_rate->tax_rate }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('products.tax_id.' . $key))
                                                <span class="fv-plugins-message-container invalid-feedback"
                                                    role="alert">
                                                    <strong>{{ $errors->first('products.tax_id.' . $key) }}</strong>
                                                </span>
                                            @endif
                                            <span class="d-flex">Value : <span
                                                    id="tax_value{{ $key }}">0</span></span>
                                            <input type="hidden" class="form-control form-control-sm tax_val"
                                                id="tax_val{{ $key }}" data-loopkey="{{ $key }}"
                                                name="products[tax_value][]" value="{{ $data['tax_value'][$key] }}"
                                                style="width: 100px;">
                                        </td>

                                        <td>
                                            <select name="products[discount_type][]"
                                                id="discount_type{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                aria-label="{{ __('Discount Type') }}"
                                                data-placeholder="{{ __('Discount Type..') }}"
                                                class="form-select form-select-sm form-select-solid discount_type"
                                                data-allow-clear="true">
                                                <option value="">{{ __('Discount Type..') }}</option>
                                                <option value="1"
                                                    {{ 1 == old('discount_type', $data['discount_type'][$key]) ? 'selected' : 'selected' }}>
                                                    Fixed</option>
                                                <option value="2"
                                                    {{ 2 == old('discount_type', $data['discount_type'][$key]) ? 'selected' : '' }}>
                                                    Percentage</option>
                                            </select>
                                            @if ($errors->has('products.discount_type.' . $key))
                                                <span class="fv-plugins-message-container invalid-feedback"
                                                    role="alert">
                                                    <strong>{{ $errors->first('products.discount_type.' . $key) }}</strong>
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <input type="number" class="form-control form-control-sm discount_amount"
                                                id="discount_amount{{ $key }}"
                                                data-loopkey="{{ $key }}" name="products[discount_amount][]"
                                                value="{{ $data['discount_amount'][$key] }}" placeholder="Amount"
                                                style="width: 100px;">

                                            <input type="number"
                                                class="form-control form-control-sm discount_percentage"
                                                id="discount_percentage{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[discount_percentage][]"
                                                value="{{ $data['discount_percentage'][$key] }}"
                                                placeholder="Percentage" style="width: 100px; display:none;"
                                                min="0" max="100">
                                            <input type="hidden"
                                                class="form-control form-control-sm discount_percentage_amount_val"
                                                id="discount_percentage_amount_val{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[discount_percentage_amount_val][]"
                                                value="{{ $data['discount_percentage_amount_val'][$key] }}"
                                                style="width: 100px;">
                                            <span class="d-flex">Amount : <span
                                                    id="discount_percentage_amount{{ $key }}">
                                                    0</span></span>
                                        </td>
                                    @endif

                                    @if (Request::is(
                                            '*/customer-sales',
                                            '*/customer-sales/*',
                                            '*/sales-order',
                                            '*/sales-order/*',
                                            '*/store-sales',
                                            '*/store-sales/*'))
                                        <td>
                                            <h3 class="inc_expense_amount"
                                                id="inc_expense_amount{{ $key }}"
                                                data-loopkey="{{ $key }}" style="width: 100px;">
                                                {{ @$data['expense_amount'][$key] }}</h3>
                                        </td>
                                    @endif

                                    <td>
                                        <h3 class="sub_total" id="sub_total{{ $key }}"
                                            data-loopkey="{{ $key }}" style="width: 100px;">
                                            {{ $data['sub_total'][$key] }}</h3>
                                        <input type="hidden" class="form-control form-control-sm sub_total_val"
                                            id="sub_total_val{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[sub_total][]"
                                            value="{{ $data['sub_total'][$key] }}" style="width: 100px;" readonly>
                                    </td>

                                    @if (Request::is(
                                            '*/customer-sales',
                                            '*/customer-sales/*',
                                            '*/sales-order',
                                            '*/sales-order/*',
                                            '*/store-sales',
                                            '*/store-sales/*'))
                                        <td>
                                            <input type="number"
                                                class="form-control form-control-sm commission_percentage_box"
                                                id="commission_percentage_box{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[commission_percentage][]"
                                                value="{{ $data['commission_percentage'][$key] }}"
                                                style="width: 100px;" readonly>

                                            <span class="d-flex">Amount : <span
                                                    id="commission_percentage_amount{{ $key }}">
                                                    {{ $data['commission_amount'][$key] }}</span></span>
                                            <input type="hidden"
                                                class="form-control form-control-sm commission_amount_val"
                                                id="commission_amount_val{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[commission_amount][]"
                                                value="{{ $data['commission_amount'][$key] }}" style="width: 100px;"
                                                readonly>
                                        </td>

                                        <td>
                                            <h3 class="total" id="total{{ $key }}"
                                                data-loopkey="{{ $key }}" style="width: 100px;">
                                                {{ $data['total'][$key] }}</h3>
                                            <input type="hidden" class="form-control form-control-sm total_val"
                                                id="total_val{{ $key }}"
                                                data-loopkey="{{ $key }}" name="products[total][]"
                                                value="{{ $data['total'][$key] }}" style="width: 100px;" readonly>
                                        </td>
                                    @endif
                                @endif

                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove_item"
                                        data-id="{{ $product->id }}"><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($data as $key => $item)
                            @php
                                $product = App\Models\Product::findOrfail($item['product_id']);
                            @endphp
                            <tr class="product_row current_row{{ $product->id }}">
                                @if (Request::is(
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*',
                                        '*/product-transfer',
                                        '*/product-transfer/*'))
                                    <td style="width: 50px;">
                                        <input type="checkbox" data-loopkey="{{ $key }}"
                                            class="form-check-input expense_billable_product_checkbox"
                                            id="expense_billable_product_checkbox{{ $key }}"
                                            name="products[is_inc_exp_billable][]" value="1"
                                            {{ (@$item->is_inc_exp_billable != null ? $item->is_inc_exp_billable == 1 : $item->is_inc_exp_billable_for_all == 1) ? 'checked' : '' }}
                                            disabled>
                                        <input type="hidden" data-loopkey="{{ $key }}"
                                            class="form-control form-control-sm inc_exp_amount"
                                            id="inc_exp_amount{{ $key }}" name="products[inc_exp_amount][]"
                                            value="{{ $item->inc_exp_amount }}">
                                    </td>
                                @endif

                                <td>
                                    <input type="hidden" class="form-control form-control-sm product"
                                        data-loopkey="{{ $key }}" value="{{ $item->id }}"
                                        name="products[id][]">
                                    <input type="hidden" class="form-control form-control-sm product"
                                        data-loopkey="{{ $key }}" value="{{ $product->id }}"
                                        name="products[product_id][]" required>
                                    {{ __($product->sku_code != null ? $product->name . ' - ' . $product->sku_code : $product->name) }}
                                </td>

                                @if (Request::is(
                                        '*/store-indent-request',
                                        '*/store-indent-request/*',
                                        '*/customer-indent-request',
                                        '*/customer-indent-request/*',
                                        '*/warehouse-indent-request',
                                        '*/warehouse-indent-request/*',
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/order-request',
                                        '*/order-request/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*',
                                        '*/sales-return',
                                        '*/sales-return/*',
                                        '*/purchase-return',
                                        '*/purchase-return/*',
                                        '*/product-transfer',
                                        '*/product-transfer/*'))
                                    <td>
                                        <select name="products[unit_id][]" id="unit_id{{ $key }}"
                                            data-loopkey="{{ $key }}"
                                            aria-label="{{ __('Search Unit') }}" data-control="select2"
                                            data-placeholder="{{ __('Search Unit..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Search Unit..') }}</option>
                                            @foreach ($units as $keys => $unit)
                                                <option data-kt-flag="{{ $unit->id }}"
                                                    value="{{ $unit->id }}"
                                                    {{ $unit->id == old('unit_id', $item->unit_id != null ? $item->unit_id : $product->unit_id) ? 'selected' : '' }}>
                                                    {{ $unit->unit_short_code }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif

                                @if (Request::is('*/adjustment', '*/adjustment/*'))
                                    <td>
                                        <select name="products[type][]" id="type{{ $key }}"
                                            data-loopkey="{{ $key }}"
                                            aria-label="{{ __('Search Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Search Type..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Search Type..') }}</option>
                                            <option value="1"
                                                {{ 1 == old('type', $item->type != null ? $item->type : '') ? 'selected' : '' }}>
                                                Addition</option>
                                            <option value="2"
                                                {{ 2 == old('type', $item->type != null ? $item->type : '') ? 'selected' : '' }}>
                                                Subtraction</option>
                                        </select>
                                    </td>
                                @endif

                                @if (Request::is('*/adjustment', '*/adjustment/*'))
                                    <td>
                                        <input type="text" class="form-control form-control-sm quantity"
                                            id="quantity{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[quantity][]" value="{{ @$item['quantity'] }}"
                                            style="width: 100px;"
                                            {{ \Request::is('*/order-request', '*/order-request/*') ? 'readonly' : 'required' }}>
                                    </td>

                                    <td>
                                        <textarea class="form-control form-control-sm remarks" id="remarks{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[remarks][]">{{ @$item['remarks'] }}</textarea>
                                    </td>
                                @else
                                    <td>
                                        <input type="text" class="form-control form-control-sm quantity"
                                            id="quantity{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[quantity][]"
                                            value="{{ @$item['request_quantity'] != null ? @$item['request_quantity'] : @$item['quantity'] }}"
                                            style="width: 100px;"
                                            {{ \Request::is('*/order-request', '*/order-request/*') ? 'readonly' : 'required' }}>
                                    </td>
                                @endif

                                @if (Request::is(
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/order-request',
                                        '*/order-request/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*'))
                                    <td>
                                        <input type="text" class="form-control form-control-sm given_quantity"
                                            id="given_quantity{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[given_quantity][]"
                                            value="{{ @$item['given_quantity'] != null ? @$item['given_quantity'] : @$item['quantity'] }}"
                                            style="width: 100px;" required>
                                    </td>
                                @endif

                                @if (Request::is(
                                        '*/customer-indent-request',
                                        '*/customer-indent-request/*',
                                        '*/warehouse-indent-request',
                                        '*/warehouse-indent-request/*',
                                        '*/purchase-order',
                                        '*/purchase-order/*',
                                        '*/order-request',
                                        '*/order-request/*',
                                        '*/customer-sales',
                                        '*/customer-sales/*',
                                        '*/sales-order',
                                        '*/sales-order/*',
                                        '*/store-sales',
                                        '*/store-sales/*',
                                        '*/sales-return',
                                        '*/sales-return/*',
                                        '*/purchase-return',
                                        '*/purchase-return/*'))
                                    <td>
                                        <input type="text" class="form-control form-control-sm amount"
                                            id="amount{{ $key }}" data-loopkey="{{ $key }}"
                                            name="products[amount][]"
                                            value="{{ $item->amount != null ? $item->amount : $item->total }}"
                                            style="width: 100px;">
                                        <span class="d-flex">Per Unit : </span><span
                                            id="per_unit_amount{{ $key }}">{{ $item->per_unit_price }}</span>
                                        <input type="hidden" class="form-control form-control-sm per_unit_amount_val"
                                            id="per_unit_amount_val{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[per_unit_price][]"
                                            value="{{ $item->per_unit_price }}" style="width: 100px;">
                                    </td>

                                    @if (Request::is(
                                            '*/purchase-order',
                                            '*/purchase-order/*',
                                            '*/customer-sales',
                                            '*/customer-sales/*',
                                            '*/sales-order',
                                            '*/sales-order/*',
                                            '*/store-sales',
                                            '*/store-sales/*'))
                                        <td>
                                            <select name="products[tax_id][]" id="tax_rate{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                aria-label="{{ __('Search Tax') }}" data-control="select2"
                                                data-placeholder="{{ __('Search Tax..') }}"
                                                class="form-select form-select-sm form-select-solid tax_rate"
                                                data-allow-clear="true">
                                                <option value="">{{ __('Search Tax..') }}</option>
                                                @foreach ($tax_rates as $keys => $tax_rate)
                                                    <option data-kt-flag="{{ $tax_rate->id }}"
                                                        data-tax_rate="{{ $tax_rate->tax_rate }}"
                                                        value="{{ $tax_rate->id }}"
                                                        {{ $tax_rate->id == old('tax_id', $item->tax_id) ? 'selected' : '' }}>
                                                        {{ $tax_rate->tax_name . ' - ' . $tax_rate->tax_rate }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="d-flex">Value : <span
                                                    id="tax_value{{ $key }}">0</span></span>
                                            <input type="hidden" class="form-control form-control-sm tax_val"
                                                id="tax_val{{ $key }}" data-loopkey="{{ $key }}"
                                                name="products[tax_value][]" value="{{ $item->tax_value }}"
                                                style="width: 100px;">
                                        </td>

                                        <td>
                                            <select name="products[discount_type][]"
                                                id="discount_type{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                aria-label="{{ __('Discount Type') }}"
                                                data-placeholder="{{ __('Discount Type..') }}"
                                                class="form-select form-select-sm form-select-solid discount_type"
                                                data-allow-clear="true" style="min-width: 100px;">
                                                <option value="">{{ __('Discount Type..') }}</option>
                                                <option value="1"
                                                    {{ 1 == old('discount_type', $item->discount_type) ? 'selected' : 'selected' }}>
                                                    Fixed</option>
                                                <option value="2"
                                                    {{ 2 == old('discount_type', $item->discount_type) ? 'selected' : '' }}>
                                                    Percentage</option>
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" class="form-control form-control-sm discount_amount"
                                                id="discount_amount{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[discount_amount][]"
                                                value="{{ $item->discount_amount }}" placeholder="Amount"
                                                style="width: 100px;">
                                            <input type="number"
                                                class="form-control form-control-sm discount_percentage"
                                                id="discount_percentage{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[discount_percentage][]"
                                                value="{{ $item->discount_percentage }}" placeholder="Percentage"
                                                style="width: 100px; display:none" min="0" max="100">
                                            <input type="hidden"
                                                class="form-control form-control-sm discount_percentage_amount_val"
                                                id="discount_percentage_amount_val{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[discount_percentage_amount_val][]"
                                                value="{{ $item->amount }}" style="width: 100px;">
                                            <span class="d-flex">Amount : <span
                                                    id="discount_percentage_amount{{ $key }}">
                                                    0</span></span>
                                        </td>
                                    @endif

                                    @if (Request::is(
                                            '*/customer-sales',
                                            '*/customer-sales/*',
                                            '*/sales-order',
                                            '*/sales-order/*',
                                            '*/store-sales',
                                            '*/store-sales/*'))
                                        <td>
                                            <h3 class="inc_expense_amount"
                                                id="inc_expense_amount{{ $key }}"
                                                data-loopkey="{{ $key }}" style="width: 100px;">
                                                {{ $item->expense_amount }}</h3>
                                        </td>
                                    @endif

                                    <td>
                                        <h3 class="sub_total" id="sub_total{{ $key }}"
                                            data-loopkey="{{ $key }}" style="width: 100px;">
                                            {{ $item->sub_total }}</h3>
                                        <input type="hidden" class="form-control form-control-sm sub_total_val"
                                            id="sub_total_val{{ $key }}"
                                            data-loopkey="{{ $key }}" name="products[sub_total][]"
                                            value="{{ $item->sub_total }}" style="width: 100px;">
                                    </td>

                                    @if (Request::is(
                                            '*/customer-sales',
                                            '*/customer-sales/*',
                                            '*/sales-order',
                                            '*/sales-order/*',
                                            '*/store-sales',
                                            '*/store-sales/*'))
                                        <td>
                                            <input type="number"
                                                class="form-control form-control-sm commission_percentage_box"
                                                id="commission_percentage_box{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[commission_percentage][]"
                                                value="{{ $item->commission_percentage }}" style="width: 100px;"
                                                readonly>
                                            <span class="d-flex">Amount : <span
                                                    id="commission_percentage_amount{{ $key }}">
                                                    {{ $item->commission_amount }}</span></span>
                                            <input type="hidden"
                                                class="form-control form-control-sm commission_amount_val"
                                                id="commission_amount_val{{ $key }}"
                                                data-loopkey="{{ $key }}"
                                                name="products[commission_amount][]"
                                                value="{{ $item->commission_amount }}" style="width: 100px;"
                                                readonly>
                                        </td>

                                        <td>
                                            <h3 class="total" id="total{{ $key }}"
                                                data-loopkey="{{ $key }}" style="width: 100px;">
                                                {{ $item->total }}</h3>
                                            <input type="hidden" class="form-control form-control-sm total_val"
                                                id="total_val{{ $key }}"
                                                data-loopkey="{{ $key }}" name="products[total][]"
                                                value="{{ $item->total }}" style="width: 100px;" readonly>
                                        </td>
                                    @endif
                                @endif

                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove_item"
                                        data-id="{{ $product->id }}"><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            </tbody>
        </table>
    </div>
</div>
