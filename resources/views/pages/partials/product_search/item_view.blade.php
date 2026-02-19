<div class="row mb-6">
    <div class="table-responsive">
        <table class="table table-sm table-rounded table-striped border gy-4 gs-7">
            <thead class="table-light">
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200 fw-bolder">
                    @if (Request::is('*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/purchase-credit', '*/purchase-credit/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                        <th style="width: 50px;">
                            <label for="expense_billable_all_checkbox">Is Billable</label>
                            <input type="checkbox" class="form-check-input expense_billable_all_checkbox" id="expense_billable_all_checkbox" name="is_inc_exp_billable_for_all" value="1" {{ old('is_inc_exp_billable_for_all', @$main_data->is_inc_exp_billable_for_all) == 1 ? 'checked' : '' }} disabled>
                        </th>
                    @endif

                    <th style="width: 400px;">{{ __('Product Name') }}</th>

                    @if (Request::is('*/store-indent-request', '*/store-indent-request/*', '*/customer-indent-request', '*/customer-indent-request/*', '*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/order-request', '*/order-request/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                        <th style="width: 150px;">{{ __('Unit') }}</th>
                    @endif

                    @if (Request::is('*/adjustment', '*/adjustment/*'))
                        <th style="width: 150px;">{{ __('Type') }}</th>
                    @endif

                    <th>{{ __('Quantity') }}</th>


                    @if (Request::is('*/adjustment', '*/adjustment/*'))
                        <th style="width: 150px;">{{ __('Remarks') }}</th>
                    @endif

                    @if (Request::is('*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/order-request', '*/order-request/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                        <th>{{ __('Given Quantity') }}</th>
                    @endif

                    @if (Request::is('*/customer-indent-request', '*/customer-indent-request/*', '*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/order-request', '*/order-request/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                        <th>{{ __('Amount') }}</th>
                        @if (Request::is('*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                            <th style="width: 200px;">{{ __('Tax') }}</th>
                            <th>{{ __('Discount Type') }}</th>
                            <th>{{ __('Amount/') }} <br> {{ __('Percentage') }}</th>
                        @endif
                        @if (Request::is('*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                            <th>{{ __('Expense Amount') }}</th>
                        @endif

                        <th>{{ __('Sub Total') }}</th>

                        @if (Request::is('*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                            <th style="width: 200px;">{{ __('Commission Percentage') }}</th>
                            <th>{{ __('Total') }}</th>
                        @endif
                        <th>{{ __('Box No') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody class="appendData">
                @if (!empty($data) && count($data) > 0)
                    @foreach ($data as $key => $item)
                        @php
                            $product = App\Models\Product::findOrfail($item['product_id']);
                        @endphp
                        <tr class="product_row current_row{{ $product->id }}">
                            @if (Request::is('*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/product-transfer', '*/product-transfer/*'))
                                <td style="width: 50px;">
                                    <input type="checkbox" data-loopkey="{{ $key }}" class="form-check-input expense_billable_product_checkbox" id="expense_billable_product_checkbox{{ $key }}" name="products[is_inc_exp_billable][]" value="1" {{ @$item->is_inc_exp_billable == 1 ? 'checked' : '' }} disabled>
                                </td>
                            @endif
                            <td>
                                {{ __($product->sku_code != null ? $product->name . ' - ' . $product->sku_code : $product->name) }}
                            </td>

                            @if (Request::is('*/store-indent-request', '*/store-indent-request/*', '*/customer-indent-request', '*/customer-indent-request/*', '*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/order-request', '*/order-request/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*', '*/product-transfer', '*/product-transfer/*'))
                                <td>
                                    <h5>{{ $item->unit_id != null ? $item->unit_details->unit_short_code : (isset($units[0]) ? $units[0]->unit_short_code : null) }}</h5>
                                </td>
                            @endif

                            <td>
                                <h5>{{ @$item['request_quantity'] }}</h5>
                            </td>

                            @if (Request::is('*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/order-request', '*/order-request/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                                <td>
                                    <h5>{{ @$item['given_quantity'] }}</h5>
                                </td>
                            @endif

                            @if (Request::is('*/customer-indent-request', '*/customer-indent-request/*', '*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/order-request', '*/order-request/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                                <td>
                                    <h5>{{ @$item['amount'] }}</h5>

                                    <span class="d-flex">Per Unit : </span><span>{{ $item->per_unit_price!=null ? $item->per_unit_price : 0.00 }}</span>
                                </td>

                                @if (Request::is('*/purchase-order', '*/purchase-order/*', '*/purchase-credit', '*/purchase-credit/*', '*/vendor-sales', '*/vendor-sales/*', '*/sales-order', '*/sales-order/*', '*/store-sales', '*/store-sales/*'))
                                    <td>
                                        <h5>
                                            <h5>{{ $item->tax_id != null ? $item->tax_details->tax_name." ".$item->tax_details->tax_rate."%" : (isset($tax_rates[0]) ? $tax_rates[0]->tax_name." ".$tax_rates[0]->tax_rate."%" : null) }}</h5>
                                        </h5>
                                        <span class="d-flex">Value : <span>{{ $item->tax_value }}</span></span>
                                    </td>

                                    <td>
                                        <p>{{ $item->discount_type != null && $item->discount_type == 2 ? "Percentage" : "Fixed" }}</p>
                                    </td>

                                    <td>
                                        @if ($item->discount_type != null && $item->discount_type == 2)
                                            <p>{{ $item->discount_percentage }}</p>
                                        @else
                                            <p>{{ $item->discount_amount }}</p>
                                        @endif
                                        <span class="d-flex">Amount : <span id="discount_percentage_amount{{ $key }}"> 0</span></span>
                                    </td>
                                @endif

                                <td>
                                    <h5 class="sub_total" data-loopkey="{{ $key }}" style="width: 100px;">{{ $item->sub_total }}</h5>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
