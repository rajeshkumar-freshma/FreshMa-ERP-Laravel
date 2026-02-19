<div class="row mb-3">
    @if (!Request::is('*/purchase-credit', '*/purchase-credit/*'))
        <div class="col-md-6">
            <div class="mb-5">
                <label for="po_item" class="required form-label">{{ __('Product') }}</label>
                <div class="input-group input-group-sm flex-nowrap">
                    <span class="input-group-text border-0"><i class="fas fa-fish fs-4"></i></span>
                    <div class="overflow-hidden flex-grow-1">
                        <input type="text" class="form-control form-control-sm typeahead" id="product_name"
                            placeholder="Enter Product Name" autocomplete="off">
                        <input type="hidden" class="form-control form-control-sm" id="product_id"
                            placeholder="Enter Product ID">
                    </div>
                    <span class="input-group-text border-0"><i class="fas fa-plus-circle fs-4"></i></span>
                </div>
            </div>
        </div>
    @endif

    @if (Request::is('*/warehouse-indent-request', '*/warehouse-indent-request/*', '*/purchase-order', '*/purchase-order/*'))
        <div class="col-md-3">
            <div class="mb-5">
                <label for="supplier_id" class="required form-label">{{ __('Supplier') }}</label>
                <div class="input-group input-group-sm flex-nowrap">
                    <span class="input-group-text border-0"><i class="fas fa-user fs-4"></i></span>
                    <div class="overflow-hidden flex-grow-1">
                        <select name="supplier_id" id="supplier_id" aria-label="{{ __('Search Supplier') }}"
                            data-control="select2" data-placeholder="{{ __('Search Supplier..') }}"
                            class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                            <option value="">{{ __('Search Supplier..') }}</option>
                            @foreach ($suppliers as $key => $supplier)
                                <option data-kt-flag="{{ $supplier->id }}" value="{{ $supplier->id }}"
                                    {{ $supplier->id == old('supplier_id', @$indent_request->supplier_id) ? 'selected' : '' }}>
                                    {{ $supplier->first_name . '-' . $supplier->last_name . '-' . $supplier->user_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <span class="input-group-text border-0"><i class="fas fa-plus-circle fs-4"></i></span>
                </div>
                @if ($errors->has('supplier_id'))
                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                    <strong>{{ $errors->first('supplier_id') }}</strong>
                </span>
            @endif
            </div>
        </div>

        @if (Request::is('*/purchase-order', '*/purchase-order/*'))
            <div class="col-md-3">
                <div class="mb-5">
                    <label for="stock_verified" class="required form-label">{{ __('Stock Verified') }}</label>
                    <div class="overflow-hidden flex-grow-1">
                        <select name="stock_verified" id="stock_verified"
                            aria-label="{{ __('Search Stock Verified') }}" data-control="select2"
                            data-placeholder="{{ __('Search Stock Verified..') }}"
                            class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                            <option value="">{{ __('Search Stock Verified..') }}</option>
                            @foreach (config('app.is_half_day') as $key => $value)
                            <option data-kt-flag="{{ $value['value'] }}"
                                value="{{ $value['value'] }}"
                                {{ $value['value'] == old('status', @$indent_request->stock_verified) ? 'selected' : '' }}>
                                {{ $value['name'] }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if (Request::is('*/store-indent-request', '*/store-indent-request/*'))
        <div class="col-md-6">
            <div class="mb-5">
                <label for="stock_transferred" class="required form-label">{{ __('Is Stock Transferred') }}</label>
                <div class="overflow-hidden flex-grow-1">
                    <select name="stock_transferred" id="stock_transferred"
                        aria-label="{{ __('Search Stock Transferred') }}" data-control="select2"
                        data-placeholder="{{ __('Search Stock Transferred..') }}"
                        class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                        @foreach (config('app.is_stock_transferred') as $key => $value)
                        <option data-kt-flag="{{ $value['value'] }}"
                            value="{{ $value['value'] }}"
                            {{ $value['value'] == old('stock_transferred', @$store_indent_request->stock_transferred) ? 'selected' : '' }}>
                            {{ $value['name'] }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
</div>

