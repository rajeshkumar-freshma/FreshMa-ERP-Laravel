<x-default-layout>
    <!--begin::Card header-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Stock Management',
            'menu_1_link' => route('admin.stock-management.index'),
            'menu_2' => 'Edit Stock Details',
        ])
    @endsection
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Stock Details'])
        <!--begin::Card header-->
    </div>

    <!--begin::Form-->
    <form id="warehouse_stock_update_form" class="form" method="POST" action="{{ route('admin.stock-management.update', $warehouse_stock_update->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="warehouse_stock_update">
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="total_stock" class="required form-label">{{ __('Stock') }}</label>
                                {{-- <input type="text" class="form-control form-control-sm form-control-solid" id="total_stock" name="total_stock" value="{{ old('total_stock', $warehouse_stock_update->total_stock) }}" placeholder="Stock-" required /> --}}
                                <h3>{{ $warehouse_stock_update->total_stock }}</h3>
                                {{-- @if ($errors->has('total_stock'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('total_stock') }}</strong>
                                    </span>
                                @endif --}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="box_number" class="required form-label">{{ __('Box Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="box_number" name="box_number" value="{{ old('box_number', $warehouse_stock_update->box_number) }}" placeholder="Stock-" required />
                                @if ($errors->has('box_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('box_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class="required form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', $warehouse_stock_update->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                    </div>
                </div>
            </div>
            <!--end::Card body-->

            @include('pages.product.stock_management.update_history', ['histories' => $warehouse_stock_update->warehouse_stock_update_histories])

            <!--begin::Actions-->
            @include('pages.partials.form_footer',['is_save' => true, 'back_url' => route('admin.stock-management.index')])
            <!--end::Actions-->
        </div>
        <!--end::Content-->
    </form>
    <!--end::Form-->
</x-default-layout>
