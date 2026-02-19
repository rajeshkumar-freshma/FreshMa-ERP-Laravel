<x-default-layout>
    <!--begin::Card header-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Product Fish Cutting Mapping',
            'menu_1_link' => route('admin.fish-cutting-product-map.index'),
            'menu_2' => 'Edit Product Fish Cutting Mapping',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Fish Cutting Product Mapping'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="fish_cutting_product_details" class="collapse show">
            <!--begin::Form-->
            <form id="fish_cutting_product_details_form" class="form" method="POST" action="{{ route('admin.fish-cutting-product-map.update', $fish_cutting_data->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Product') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="main_product_id" id="main_product_id" aria-label="{{ __('Select Product') }}" data-control="select2" data-placeholder="{{ __('Select Product..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Product..') }}</option>
                                            @foreach ($products as $key => $product)
                                                <option data-kt-flag="{{ $product->id }}" value="{{ $product->id }}" {{ $product->id == old('main_product_id', $fish_cutting_data->main_product_id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('main_product_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('main_product_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $fish_cutting_data->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('status'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="wastage_percentage" class="form-label">{{ __('Wastage Percentage') }}</label>
                                <input type="number" name="wastage_percentage" class="form-control form-control-sm form-control-solid" id="wastage_percentage" value="{{ old('wastage_percentage', $fish_cutting_data->wastage_percentage) }}"placeholder="Enter Percentage" step="any" required>
                                @if ($errors->has('wastage_percentage'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('wastage_percentage') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="remarks" class="form-label required">{{ __('Remarks') }}</label>
                                <textarea name="remarks" id="remarks" rows="5" class="form-control form-control-solid">{{ old('remarks', $fish_cutting_data->remarks) }}</textarea>
                                @if ($errors->has('percentage'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('percentage') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--End::Input group-->

                    <div class="row mb-3">
                        <h2>Cutting Product Map</h2>
                        <hr>
                        @if ($fish_cutting_data->grouped_product != null && $fish_cutting_data->grouped_product != 'null')
                            @foreach (json_decode($fish_cutting_data->grouped_product, true) as $key => $grouped_product)
                                <div class="row product_div">
                                    <div class="col-md-3">
                                        <div class="mb-5">
                                            <label for="name" class="required form-label">{{ __('Product') }}</label>
                                            <div class="input-group input-group-sm flex-nowrap">
                                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                <div class="overflow-hidden flex-grow-1">
                                                    <select name="products[{{ $key }}][product_id]" id="product_id" aria-label="{{ __('Select Product') }}" data-control="select2" data-placeholder="{{ __('Select Product..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                                        <option value="">{{ __('Select Product..') }}</option>
                                                        @foreach ($products as $keys => $product)
                                                            <option data-kt-flag="{{ $product->id }}" value="{{ $product->id }}" {{ $product->id == old('product_id', $grouped_product['product_id']) ? 'selected' : '' }}>{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($errors->has('product_id'))
                                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('product_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-5">
                                            <label for="type" class="form-label required">{{ __('Type') }}</label>
                                            <div class="input-group input-group-sm flex-nowrap">
                                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                <div class="overflow-hidden flex-grow-1">
                                                    <select name="products[{{ $key }}][type]" id="type" aria-label="{{ __('Select Type') }}" data-control="select2" data-placeholder="{{ __('Select Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                                        <option value="">{{ __('Select Type..') }}</option>
                                                        @foreach (config('app.fish_cutting_type') as $keys => $value)
                                                            <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('type', $grouped_product['type']) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($errors->has('status'))
                                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-5">
                                            <label for="percentage" class="form-label required">{{ __('Percentage') }}</label>
                                            <input type="number" name="products[{{ $key }}][percentage]" class="form-control form-control-sm form-control-solid" id="percentage" placeholder="Enter Percentage" value="{{ $grouped_product['percentage'] }}"step="any" required>
                                            @if ($errors->has('percentage'))
                                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('percentage') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mt-8">
                                            <label for="percentage" class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-sm btn-info product_append_button"><i class="fa fa-plus"></i></button>
                                            @if ($key > 0)
                                                <button type="button" class="btn btn-sm btn-danger remove_button"><i class="fa fa-close"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <span class="product_append_div"></span>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.fish-cutting-product-map.index')])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
        <script>
            $(function() {
                $('body').on('click', '.remove_button', function() {
                    $(this).parent().parent().parent().remove();
                });
                $('body').on('click', '.product_append_button', function() {
                    var count = $('.product_div').length || 1;
                    var append_data =
                        '<div class="row mb-3 product_div">' +
                        '<div class="col-md-3">' +
                        '<div class="mb-5">' +
                        '<label for="name" class="required form-label">{{ __('Product') }}</label>' +
                        '<div class="input-group input-group-sm flex-nowrap">' +
                        '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                        '<div class="overflow-hidden flex-grow-1">' +
                        '<select name="products[' + count + '][product_id]" id="product_id" aria-label="{{ __('Select Product') }}" data-control="select2" data-placeholder="{{ __('Select Product..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>' +
                        '<option value="">{{ __('Select Product..') }}</option>' +
                        @foreach ($products as $key => $product)
                            '<option data-kt-flag="{{ $product->id }}" value="{{ $product->id }}" {{ $product->id == old('product_id') ? 'selected' : '' }}>{{ $product->name }}</option>' +
                        @endforeach
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    @if ($errors->has('product_id'))
                        '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                        '<strong>{{ $errors->first('product_id') }}</strong>' +
                        '</span>' +
                    @endif
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label for="type" class="form-label required">{{ __('Type') }}</label>' +
                    '<div class="input-group input-group-sm flex-nowrap">' +
                    '<span class="input-group-text border-0"><i class="fas fa-home"></i></span>' +
                    '<div class="overflow-hidden flex-grow-1">' +
                    '<select name="products[' + count + '][type]" id="type" aria-label="{{ __('Select Type') }}" data-control="select2" data-placeholder="{{ __('Select Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>' +
                        '<option value="">{{ __('Select Type..') }}</option>' +
                        @foreach (config('app.fish_cutting_type') as $key => $value)
                            '<option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>' +
                        @endforeach
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    @if ($errors->has('type'))
                        '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                        '<strong>{{ $errors->first('type') }}</strong>' +
                        '</span>' +
                    @endif
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mb-5">' +
                    '<label for="percentage" class="form-label required">{{ __('Percentage') }}</label>' +
                    '<input type="number" name="products[' + count + '][percentage]" class="form-control form-control-sm form-control-solid" id="percentage" placeholder="Enter Percentage" step="any" required>' +
                        @if ($errors->has('percentage'))
                            '<span class="fv-plugins-message-container invalid-feedback" role="alert">' +
                            '<strong>{{ $errors->first('percentage') }}</strong>' +
                            '</span>' +
                        @endif
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-3">' +
                    '<div class="mt-8">' +
                    '<button type="button" class="btn btn-sm btn-info product_append_button"><i class="fa fa-plus"></i></button>&nbsp;' +
                    '<button type="button" class="btn btn-sm btn-danger remove_button"><i class="fa fa-close"></i></button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                    $('.product_append_div').append(append_data);
                    $('.form-select').select2({
                        theme: 'bootstrap4',
                        dropdownParent: $('.product_append_div')
                    });
                    $('.form-select').select2();
                    // $('.select2-container').remove();
                });
            });
        </script>
    @endsection
</x-default-layout>
