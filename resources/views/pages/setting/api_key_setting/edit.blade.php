<x-default-layout>

    <div class="card">
        <div class="box-content">
            <!--begin::Header-->
            <div>
                @if ($errors->any())
                    <div class="alert alert-info">{{ $errors->first() }}</div>
                @endif
            </div>
            <div class="card-header card-header-stretch">
                <!--begin::Title-->
                <div class="card-title">
                    <h3>Edit Api Key</h3>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="card-body pt-6">
                            <form action="{{ route('admin.api-keys.update', @$apiKeySetting->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4 mb-5">
                                        <label for="name" class="form-label required">{{ __('Name') }}</label>
                                        <input type="text" name="name"
                                            value="{{ old('name', @$apiKeySetting->name) }}"
                                            class="form-control form-control-sm form-control-solid" id="name"
                                            placeholder="Enter Name.." required />
                                        @error('name')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-5">
                                        <label for="api_key" class="form-label required">{{ __('Api Key') }}</label>
                                        <input type="text" name="api_key"
                                            value="{{ old('api_key', @$apiKeySetting->api_key) }}"
                                            class="form-control form-control-sm form-control-solid" id="api_key"
                                            placeholder="Enter Api Key.." required />
                                        @error('api_key')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-5">
                                        <label for="status" class="form-label required">{{ __('Status') }}</label>
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status', @$apiKeySetting->status) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end py-6">
                                            <a href="{{ route('admin.api-keys.index') }}"><button type="button"
                                                    class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('back') }}</button></a>
                                            <button type="submit"
                                                class="btn btn-sm btn-success me-2">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                    <!--begin::Label-->
                </div>
            </div>
        </div>
    </div>
    {{-- @include('pages.setting.api_key_setting.script') --}}
</x-default-layout>
