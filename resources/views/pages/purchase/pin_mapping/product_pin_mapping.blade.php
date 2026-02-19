<x-default-layout>
    <div class="card mt-2">
        <!--begin::Card header-->
        @include('pages.partials.form_collapse_header', [
            'header_name' => 'Product Pin Mapping',
            'card_key' => 'product_pin_details',
        ])
        <!--begin::Card header-->

        <div id="product_pin_details">
            {{-- <div id="product_pin_details" class="collapse"> --}}
            <!--begin::Card body-->
            <div class="card-body border-top px-9 py-4 add_more_product_pin_data">
                <!--begin::Input group-->
                {{-- @if ($is_old_data == 'old_value')
                @foreach ($product_pin_details['box_product_id'] as $key => $item)
                    <div class="row mb-3 add_more_product_pin_data product_pin_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" name="product_pin_details[box_product_id][]" value="{{ @$item->id }}"
                                id="box_product_id" class="form-control form-control-sm">
                            <div class="mb-5">
                                <label for="box_product_id" class="form-label">{{ __('Product') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="product_pin_details[box_product_id][]" id="box_product_id"
                                            aria-label="{{ __('Select Product') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Product..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Product..') }}</option>
                                            @foreach ($products as $item)
                                                <option data-kt-flag="{{ $item->id }}" value="{{ $item->id }}"
                                                    {{ $item->id == old('box_product_id', @$product_pin_details['box_product_id'][$key]) ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('box_product_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_product_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="box_no" class=" form-label">{{ __('Box No') }}</label>
                                <input id="box_no" type="text"
                                    class="form-control form-control-sm form-control-solid "
                                    name="product_pin_details[box_no][]"
                                    value="{{ old('box_no', @$product_pin_details['box_no'][$key]) }}" />
                                @if ($errors->has('box_no'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="box_weight" class=" form-label">{{ __('Weight') }}</label>
                                <input id="box_weight" type="text"
                                    class="form-control form-control-sm form-control-solid"
                                    name="product_pin_details[box_weight][]"
                                    value="{{ old('box_weight', @$product_pin_details['box_weight'][$key]) }}" />
                                @if ($errors->has('box_weight'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_weight') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label"> &nbsp;</label>
                                <button type="button" class="btn btn-sm btn-primary add_product_pin_data mt-7"><i
                                        class="fa fa-plus"></i></button>
                                @if ($key != 0)
                                    <button type="button" class="btn btn-sm btn-danger product_pin_remove_date mt-7"
                                        data-loop={{ $key }}><i class="fa fa-close"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif ($is_old_data == 'edit_value' && count($product_pin_details) > 0)
                @foreach ($product_pin_details as $key => $item)
                    <div class="row mb-3 add_more_product_pin_data product_pin_data{{ $key }}">
                        <!--begin::Label-->
                        <div class="col-md-4">
                            <input type="hidden" name="product_pin_details[box_product_id][]"
                                value="{{ @$item->id }}" id="box_product_id" class="form-control form-control-sm">
                            <div class="mb-5">
                                <label for="box_product_id" class="form-label">{{ __('Product') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="product_pin_details[box_product_id][]" id="box_product_id"
                                            aria-label="{{ __('Select Product') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Product..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Product..') }}</option>
                                            @foreach ($products as $item)
                                                <option data-kt-flag="{{ $product_id->id }}"
                                                    value="{{ $product_id->id }}"
                                                    {{ $product_id->id == old('box_product_id', @$item->box_product_id) ? 'selected' : '' }}>
                                                    {{ $product_id->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('box_product_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_product_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="box_no" class=" form-label">{{ __('Box No') }}</label>
                                <input id="box_no" type="text"
                                    class="form-control form-control-sm form-control-solid "
                                    name="product_pin_details[box_no][]" value="{{ old('box_no', @$item->box_no) }}" />
                                @if ($errors->has('box_no'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="box_weight" class=" form-label">{{ __('Weight') }}</label>
                                <input id="box_weight" type="text"
                                    class="form-control form-control-sm form-control-solid"
                                    name="product_pin_details[box_weight][]"
                                    value="{{ old('box_weight', @$item->box_weight) }}" />
                                @if ($errors->has('box_weight'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_weight') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="to_location" class=" form-label"> &nbsp;</label>
                                <button type="button" class="btn btn-sm btn-primary add_product_pin_data mt-7"><i
                                        class="fa fa-plus"></i></button>
                                @if ($key != 0)
                                    <button type="button" class="btn btn-sm btn-danger product_pin_remove_date mt-7"
                                        data-loop={{ $key }}><i class="fa fa-close"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else --}}
                <form action="{{ route('admin.product_pin_mapping_store', @$purchase_orders_details->id ?? '') }}"
                    enctype="multipart/form-data" method="post">
                    @csrf
                    @method('post')
                    <div class="row mb-3 add_more_product_pin_data product_pin_data0">
                        <!--begin::Label-->
                        @if (isset($purchase_orders_details->purchase_order_product_details) &&
                                count($purchase_orders_details->purchase_order_product_details) > 0)
                            @foreach (@$purchase_orders_details->purchase_order_product_details as $pin_details)
                                <div class="col-md-4">
                                    <div class="mb-5">
                                        <label for="box_product_id" class="form-label">{{ __('Product') }}</label>
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                            <div class="overflow-hidden flex-grow-1">
                                                <select name="product_pin_details[box_product_id][]" id="box_product_id"
                                                    aria-label="{{ __('Select Product') }}" data-control="select2"
                                                    data-placeholder="{{ __('Select Product..') }}"
                                                    class="form-select form-select-sm form-select-solid"
                                                    data-allow-clear="true">
                                                    <option value="">{{ __('Select Product..') }}</option>
                                                    @foreach ($products as $item)
                                                        <option data-kt-flag="{{ $item->id }}"
                                                            value="{{ $item->id }}"
                                                            {{ $item->id == old('box_product_id', @$pin_details->product_id) ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('box_product_id'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('box_product_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-5">
                                        <label for="box_weight" class=" form-label">{{ __('Weight') }}</label>
                                        <input id="box_weight" type="text"
                                            class="form-control form-control-sm form-control-solid"
                                            name="product_pin_details[box_weight][]"
                                            value="{{ old('box_weight', @$pin_details->given_quantity) }}" />
                                        @if ($errors->has('box_weight'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('box_weight') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-5">
                                        <label for="box_no" class=" form-label">{{ __('Box No') }}</label>
                                        <input id="box_no" type="text"
                                            class="form-control form-control-sm form-control-solid "
                                            name="product_pin_details[box_no][]"
                                            value="{{ old('box_no', @$pin_details->box_no) }}" />
                                        @if ($errors->has('box_no'))
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('box_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No Products are mapped in purchase orders details table</p>
                        @endif
                        <div class="col-md-12">
                            <div class="mb-5">
                                <div class="d-flex justify-content-end py-6">
                                    <button type="submit"
                                        class="btn btn-sm btn-success me-2 filter_button">{{ __('Submit') }}</button>
                                    <a href="{{ route('admin.purchase-order.index') }}"><button type="button"
                                            class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear') }}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @endif --}}
                <div class="product_pin_data">
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    @section('scripts')
        {{-- @include('pages.purchase.pin_mapping.product_pin_mapping_addmore') --}}
    @endsection
</x-default-layout>
