<tr class="product_row current_row{{ $product->id }}">
    @if ($expense_display == 'true')
        <td style="width: 50px;">
            <input type="checkbox" data-loopkey="{{ $count }}" class="form-check-input expense_billable_product_checkbox" id="expense_billable_product_checkbox{{ $count }}"  name="products[is_inc_exp_billable][]" value="1" disabled>
            <input type="hidden" data-loopkey="{{ $count }}" class="form-control form-control-sm inc_exp_amount" id="inc_exp_amount{{ $count }}" name="products[inc_exp_amount][]" value="0">
        </td>
    @endif
    <td>
        <input type="hidden" class="form-control form-control-sm product" data-loopkey="{{ $count }}" value="" name="products[id][]">
        <input type="hidden" class="form-control form-control-sm product" data-loopkey="{{ $count }}" value="{{ $product->id }}" name="products[product_id][]" required>
        {{ __($product->sku_code!=null ? $product->name." - ".$product->sku_code : $product->name) }}
    </td>

    @if ($unit_display == 'true')
        <td>
            <select name="products[unit_id][]" id="unit_id{{ $count }}" data-loopkey="{{ $count }}" aria-label="{{ __('Search Unit') }}" data-control="select2" data-placeholder="{{ __('Search Unit..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                <option value="">{{ __('Search Unit..') }}</option>
                @foreach($units as $key => $unit)
                    <option data-kt-flag="{{ $unit->id }}" value="{{ $unit->id }}" {{ $unit->id == old('unit_id', @$product->unit_id!=null ? $product->unit_id : $unit_id) ? 'selected' :'' }}>{{ $unit->unit_short_code }}</option>
                @endforeach
            </select>
        </td>
    @endif

    @if ($type_display == 'true')
        <td>
            <select name="products[type][]" id="type{{ $count }}" data-loopkey="{{ $count }}" aria-label="{{ __('Search Type') }}" data-control="select2" data-placeholder="{{ __('Search Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                <option value="">{{ __('Search Type..') }}</option>
                <option value="1" {{ 1 == old('type') ? 'selected' : '' }}>Addition</option>
                <option value="2" {{ 2 == old('type') ? 'selected' : '' }}>Subtraction</option>
            </select>
        </td>
    @endif

    <td>
        <input type="text" class="form-control form-control-sm quantity" id="quantity{{ $count }}" data-loopkey="{{ $count }}" name="products[quantity][]" value="{{ $quantity }}" style="width: 100px;" required>
    </td>

    @if ($given_quantity_display == 'true')
        <td>
            <input type="text" class="form-control form-control-sm given_quantity" id="given_quantity{{ $count }}" data-loopkey="{{ $count }}" name="products[given_quantity][]" value="{{ $quantity }}" style="width: 100px;" required>
        </td>
    @endif

    @if ($amountdisplay == 'true')
        <td>
            <input type="text" class="form-control form-control-sm amount" id="amount{{ $count }}" data-loopkey="{{ $count }}" name="products[amount][]" style="width: 100px;" required>

            <span class="d-flex">Per Unit : <span id="per_unit_amount{{ $count }}">0</span></span>
            <input type="hidden" class="form-control form-control-sm per_unit_amount_val" id="per_unit_amount_val{{ $count }}" data-loopkey="{{ $count }}" name="products[per_unit_price][]" value="0" style="width: 100px;">
        </td>
    @endif

    @if ($taxanddiscountdisplay == 'true')
        <td>
            <select name="products[tax_id][]" id="tax_rate{{ $count }}" data-loopkey="{{ $count }}" aria-label="{{ __('Search Tax') }}" data-control="select2" data-placeholder="{{ __('Search Tax..') }}" class="form-select form-select-sm form-select-solid tax_rate" data-allow-clear="true">
                <option value="">{{ __('Search Tax..') }}</option>
                @foreach($tax_rates as $keys => $tax_rate)
                    <option data-kt-flag="{{ $tax_rate->id }}" data-tax_rate="{{ $tax_rate->tax_rate }}" value="{{ $tax_rate->id }}" {{ $tax_rate->id == old('tax') ? 'selected' :'' }}>{{ $tax_rate->tax_name." - ".$tax_rate->tax_rate }}</option>
                @endforeach
            </select>
            <span class="d-flex">Value : <span id="tax_value{{ $count }}">0</span></span>
            <input type="hidden" class="form-control form-control-sm tax_val" id="tax_val{{ $count }}" data-loopkey="{{ $count }}" name="products[tax_value][]" style="width: 100px;">
        </td>

        <td>
            <select name="products[discount_type][]" id="discount_type{{ $count }}" data-loopkey="{{ $count }}" aria-label="{{ __('Discount Type') }}" data-control="select2" data-placeholder="{{ __('Discount Type..') }}" class="form-select form-select-sm form-select-solid discount_type" data-allow-clear="true">
                <option value="">{{ __('Discount Type..') }}</option>
                <option value="1" selected>Fixed</option>
                <option value="2">Percentage</option>
            </select>
        </td>

        <td>
            <input type="number" class="form-control form-control-sm discount_amount" id="discount_amount{{ $count }}" data-loopkey="{{ $count }}" name="products[discount_amount][]" placeholder="Discount Amount" style="width: 100px;" value="0">
            <input type="number" class="form-control form-control-sm discount_percentage" id="discount_percentage{{ $count }}" data-loopkey="{{ $count }}" name="products[discount_percentage][]" placeholder="Discount Percentage" style="width: 100px; display:none" min="0" max="100" value="0">
            <input type="hidden" class="form-control form-control-sm discount_percentage_amount_val" id="discount_percentage_amount_val{{ $count }}" data-loopkey="{{ $count }}" name="products[discount_percentage_amount_val][]" style="width: 100px;">
            <span class="d-flex">Amount : <span id="discount_percentage_amount{{ $count }}"> 0</span></span>
        </td>


        @if ($commission_and_expense_display == 'true')
            <td>
                <h3 class="inc_expense_amount" id="inc_expense_amount{{ $count }}" data-loopkey="{{ $count }}" style="width: 100px;">0</h3>
            </td>
        @endif
    @endif

        @if ($subtotaldisplay == 'true')
            <td>
                <h3 class="sub_total" id="sub_total{{ $count }}" data-loopkey="{{ $count }}" style="width: 100px;">0</h3>
                <input type="hidden" class="form-control form-control-sm sub_total_val" id="sub_total_val{{ $count }}" data-loopkey="{{ $count }}" name="products[sub_total][]" style="width: 100px;">
            </td>
        @endif

    @if ($taxanddiscountdisplay == 'true')
        @if ($commission_and_expense_display == 'true')
            <td>
                <input type="number" class="form-control form-control-sm commission_percentage_box" id="commission_percentage_box{{ $count }}" data-loopkey="{{ $count }}" name="products[commission_percentage][]" value="{{ $vendor_percentage }}" style="width: 100px;" {{ $is_editable == 1 ? '' : "readonly" }}>
                <span class="d-flex">Amount : </span><span id="commission_percentage_amount{{ $count }}"> 0</span>
                <input type="hidden" class="form-control form-control-sm commission_amount_val" id="commission_amount_val{{ $count }}" data-loopkey="{{ $count }}" name="products[commission_amount][]" value="0" style="width: 100px;" readonly>
            </td>

            <td>
                <h3 class="total" id="total{{ $count }}" data-loopkey="{{ $count }}" style="width: 100px;">0</h3>
                <input type="hidden" class="form-control form-control-sm total_val" id="total_val{{ $count }}" data-loopkey="{{ $count }}" name="products[total][]" value="0" style="width: 100px;" readonly>
            </td>
        @endif

    @endif
    {{-- <td><input type="text" id="box_no{{ $count }}" name="products[box_no][]" data-loopkey="{{ $count }}" class="w-100 box_no"></td> --}}
    <td>
        <button type="button" class="btn btn-sm btn-danger remove_item" data-id="{{ $product->id }}"><i class="fa fa-remove"></i></button>
    </td>
</tr>
