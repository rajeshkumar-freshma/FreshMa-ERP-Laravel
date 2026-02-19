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
                    <h3>Create Api Key</h3>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="card-body pt-6">
                            <form action="{{ route('admin.api-keys.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-4 mb-5">
                                        <label for="name" class="form-label required">{{ __('Name') }}</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
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
                                        <input type="text" name="api_key" value="{{ old('api_key') }}"
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
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
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
                                            <button type="submit"
                                                class="btn btn-sm btn-success me-2">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <!--begin::Card body-->
                        <div class="card-body pt-6">
                            @include('pages.setting.api_key_setting._table')
                        </div>
                        <!--end::Card body-->
                        {{-- <div class="card ">
                            <!--begin::Header-->
                            <div class="card-header card-header-stretch">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <h3>API Keys</h3>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9"
                                        id="kt_api_keys_table">
                                        <!--begin::Thead-->
                                        <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                            <tr>
                                                <th class="min-w-175px ps-9">Label</th>
                                                <th class="min-w-250px px-0">API Keys</th>
                                                <th class="min-w-100px">Created</th>
                                                <th class="min-w-100px">Status</th>
                                                <th class="w-100px"></th>
                                                <th class="w-100px"></th>
                                            </tr>
                                        </thead>
                                        <!--end::Thead-->

                                        <!--begin::Tbody-->
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td class="ps-9">
                                                    none set </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    fftt456765gjkkjhi83093985 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    Nov 01, 2020 </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-success fs-7 fw-semibold">Active</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-9">
                                                    Navitare </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    jk076590ygghgh324vd33 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    Sep 27, 2020 </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-primary fs-7 fw-semibold">Review</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-9">
                                                    Docs API Key </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    fftt456765gjkkjhi83093985 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    Jul 09, 2020 </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-danger fs-7 fw-semibold">Inactive</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-9">
                                                    Identity Key </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    jk076590ygghgh324vd3568 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    May 14, 2020 </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-success fs-7 fw-semibold">Active</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-9">
                                                    Remore Interface </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    hhet6454788gfg555hhh4 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    Dec 30, 2019 </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-success fs-7 fw-semibold">Active</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-9">
                                                    none set </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    fftt456765gjkkjhi83093985 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    Inactive </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-danger fs-7 fw-semibold">Active</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-9">
                                                    Test App </td>

                                                <td data-bs-target="license" class="ps-0">
                                                    jk076590ygghgh324vd33 </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-active-color-primary btn-color-gray-500 btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-outline ki-copy fs-2"></i> </button>
                                                </td>

                                                <td>
                                                    Apr 03, 2019 </td>

                                                <td>
                                                    <span
                                                        class="badge badge-light-success fs-7 fw-semibold">Active</span>
                                                </td>

                                                <td class="pe-9">
                                                    <div class="w-100px position-relative">
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Options"
                                                            data-hide-search="true">
                                                            <option value=""></option>
                                                            <option value="2">Options 1</option>
                                                            <option value="3">Options 2</option>
                                                            <option value="4">Options 3</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button data-action="copy"
                                                        class="btn btn-color-gray-500 btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                        <i class="ki-solid ki-copy fs-2"></i> </button>
                                                </td>
                                            </tr>

                                        </tbody>
                                        <!--end::Tbody-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Body-->
                        </div> --}}

                        <!--end::API keys-->
                        {{-- <div class="col-md-11">
                            <div class="mb-5">
                                <!-- Move this div outside the mb-5 div -->
                                <div class="input-group d-flex justify-content-end">
                                    <div class="d-flex justify-content-end py-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success btn-active-light-primary me-2"
                                            name="submit_type" value="1">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                    <!--begin::Label-->
                </div>
            </div>
        </div>
    </div>

    {{-- <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.setting.app.menu_map._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card--> --}}
    @include('pages.setting.api_key_setting.script')
</x-default-layout>
