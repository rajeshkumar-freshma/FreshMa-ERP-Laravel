<x-default-layout>
    <!--begin::Card header-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Fish Cutting',
            'menu_1_link' => route('admin.fish-cutting.index'),
            'menu_2' => 'Edit Fish Cutting',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Fish Cutting'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="fish_cutting" class="collapse show">
            <!--begin::Form-->
            <form id="fish_cutting_form" class="form" method="POST"
                action="{{ route('admin.fish-cutting.update', $fish_cutting_data->id) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
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
                                        <select name="product_id" id="product_id"
                                            aria-label="{{ __('Select Product') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Product..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Product..') }}</option>
                                            @foreach (@$products as $key => $product)
                                                <option data-kt-flag="{{ $product->id }}" value="{{ $product->id }}"
                                                    {{ $product->id == old('product_id', @$fish_cutting_data->product_id) ? 'selected' : '' }}>
                                                    {{ $product->name }}</option>
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

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Store') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach (@$stores as $key => $store)
                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}"
                                                    {{ $store->id == old('store_id', @$fish_cutting_data->store_id) ? 'selected' : '' }}>
                                                    {{ $store->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('store_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('store_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="weight" class="form-label">{{ __('Weight') }}</label>
                                <input type="text" name="weight"
                                    class="form-control form-control-sm form-control-solid" id="weight"
                                    value="{{ old('weight', @$fish_cutting_data->weight) }}" placeholder="Enter"
                                    step="any" required>
                                @if ($errors->has('weight'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('weight') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="cutting_date" class="required form-label">{{ __('Cutting Date') }}</label>
                                <div class="input-group">
                                    <input id="cutting_date" type="button"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker "
                                        name="cutting_date"
                                        value="{{ old('cutting_date', @$fish_cutting_data->cutting_date) }}"
                                        required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('cutting_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cutting_date') }}</strong>
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
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', @$fish_cutting_data->status) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
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
                                <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                                <textarea name="remarks" id="remarks" rows="5" class="form-control form-control-solid">{{ old('remarks') }}</textarea>
                                @if ($errors->has('remarks', @$fish_cutting_data->remarks))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('remarks') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--End::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.fish-cutting.index'),
                ])
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
                $(".fsh_flat_datepicker").flatpickr();
            });
        </script>
    @endsection
</x-default-layout>
