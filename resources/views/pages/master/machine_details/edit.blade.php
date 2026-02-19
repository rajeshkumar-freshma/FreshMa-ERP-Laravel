<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Machine Details',
            'menu_1_link' => route('admin.machine-details.index'),
            'menu_2' => 'Edit Machine Details',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Machine Details'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details" class="collapse show">
            <!--begin::Form-->
            <form id="category_details_form" class="form" method="POST"
                action="{{ route('admin.machine-details.update', $machine_details->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="machine_name" class="required form-label">{{ __('Machine Name') }}</label>
                                <input type="text" name="machine_name"
                                    value="{{ old('machine_name', $machine_details->MachineName) }}"
                                    class="form-control form-control-sm form-control-solid" id="machine_name"
                                    placeholder="Enter Machine Name" required />
                                @if ($errors->has('machine_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('machine_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Hidden input to store current page number -->
                        <input type="hidden" name="page" value="{{ @$currentPage }}">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="store_id" class="form-label">{{ __('Store') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="store_id" id="store_id" aria-label="{{ __('Select Store') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Store..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Store..') }}</option>
                                            @foreach ($stores as $key => $store)
                                                <option value="{{ $store->id }}"
                                                    {{ $store->id == old('store_id', $machine_details->store_id) ? 'selected' : '' }}
                                                    class="optionGroup">{{ ucFirst($store->store_name) }}</option>
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
                                <label for="port" class="required form-label">{{ __('Port Number') }}</label>
                                <input type="text" name="port" value="{{ old('port', $machine_details->Port) }}"
                                    class="form-control form-control-sm form-control-solid" id="port"
                                    placeholder="Enter Port Number" required />
                                @if ($errors->has('port'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('port') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="ip_address" class="form-label">{{ __('IP Address') }}</label>
                                <input type="text" name="ip_address"
                                    value="{{ old('ip_address', $machine_details->IPAddress) }}"
                                    class="form-control form-control-sm form-control-solid" id="ip_address"
                                    placeholder="Enter IP Address" />
                                @if ($errors->has('ip_address'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ip_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="capacity" class="required form-label">{{ __('Capacity') }}</label>
                                <input type="text" name="capacity"
                                    value="{{ old('capacity', $machine_details->Capacity) }}"
                                    class="form-control form-control-sm form-control-solid" id="capacity"
                                    placeholder="Enter Capacity" required />
                                @if ($errors->has('capacity'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('capacity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="required form-label">{{ __('Status') }}</label>

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
                                                    {{ $value['value'] == old('status', $machine_details->Status) ? 'selected' : '' }}>
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
                                <label for="plu_master_code"
                                    class="required form-label">{{ __('PLU Master Code') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="plu_master_code" id="plu_master_code"
                                            aria-label="{{ __('Select PLU Master') }}" data-control="select2"
                                            data-placeholder="{{ __('Select PLU Master..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select PLU Master..') }}</option>
                                            @foreach ($plu_master_datas as $key => $plu_master_data)
                                                <option value="{{ $plu_master_data->plu_master_code }}"
                                                    {{ $plu_master_data->plu_master_code == old('plu_master_code', $machine_details->PLUMasterCode) ? 'selected' : '' }}>
                                                    {{ $plu_master_data->plu_master_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('plu_master_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('plu_master_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="machine_status"
                                    class="required form-label">{{ __('Machine Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="machine_status" id="machine_status"
                                            aria-label="{{ __('Select Machine Status') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Machine Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Machine Status..') }}</option>
                                            @foreach (config('app.machine_status') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('machine_status', $machine_details->Online) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('machine_status'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('machine_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => false,
                    'back_url' => route('admin.machine-details.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
