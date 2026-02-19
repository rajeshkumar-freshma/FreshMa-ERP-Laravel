<div class="row mb-3">
    <div class="col-md-6">
        <div class="mb-5">
            <label for="po_item" class="required form-label">{{ __('Product') }}</label>
            <div class="input-group input-group-sm flex-nowrap">
                <span class="input-group-text border-0"><i class="fas fa-fish fs-4"></i></span>
                <div class="overflow-hidden flex-grow-1">
                    <input type="text" class="form-control form-control-sm typeahead" id="product_name" placeholder="Enter Product Name">
                    <input type="hidden" class="form-control form-control-sm" id="product_id" placeholder="Enter Product ID">
                    {{-- <select name="po_item" id="po_item" aria-label="{{ __('Search Product') }}" data-control="select2" data-placeholder="{{ __('Search Product..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                        <option value="">{{ __('Search Product..') }}</option>
                        @foreach(config('app.purchase_status') as $key => $value)
                            <option data-kt-flag="{{ $key }}" value="{{ $key }}" {{ $key == old('po_item') ? 'selected' :'' }}>{{ $value }}</option>
                        @endforeach
                    </select> --}}
                </div>
                <span class="input-group-text border-0"><i class="fas fa-plus-circle fs-4"></i></span>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-6">
        <div class="mb-5">
            <label for="po_supplier" class="required form-label">{{ __('Supplier') }}</label>
            <div class="input-group input-group-sm flex-nowrap">
                <span class="input-group-text border-0"><i class="fas fa-user fs-4"></i></span>
                <div class="overflow-hidden flex-grow-1">
                    <select name="po_supplier" id="po_supplier" aria-label="{{ __('Search Supplier') }}" data-control="select2" data-placeholder="{{ __('Search Supplier..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                        <option value="">{{ __('Search Supplier..') }}</option>
                        @foreach(config('app.purchase_status') as $key => $value)
                            <option data-kt-flag="{{ $key }}" value="{{ $key }}" {{ $key == old('po_supplier') ? 'selected' :'' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="input-group-text border-0"><i class="fas fa-plus-circle fs-4"></i></span>
            </div>
        </div>
    </div> --}}
</div>
