<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'App Menu Mapping',
            'menu_1_link' => route('admin.app-menu-mapping.index'),
            'menu_2' => 'App Menu Mapping',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add User Menu Mapping'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="app_menu_map" class="collapse show">
            <!--begin::Form-->
            <form id="app_menu_map_form" class="form" method="POST" action="{{ route('admin.app-menu-mapping.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Type') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="type" id="type" aria-label="{{ __('Select Type') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Type..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Type..') }}</option>
                                            @foreach (config('app.app_menu_mapping_type') as $item)
                                                <option value="{{ $item['value'] }}"
                                                    {{ $item['value'] == old('type') ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6 employee_div" style="display: none;">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Partner/Manager') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="admin_id" id="admin_id" aria-label="{{ __('Select Partner/Manager') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Partner/Manager..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Partner/Manager..') }}</option>
                                            @foreach ($admins as $admin)
                                                <option value="{{ $admin->id }}"
                                                    {{ $admin->id == old('admin_id') ? 'selected' : '' }}>
                                                    {{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('admin_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('admin_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6 supplier_div" style="display: none;">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Supplier') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="supplier_id" id="supplier_id"
                                            aria-label="{{ __('Select Supplier') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Supplier..') }}"
                                            class="form-select form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Supplier..') }}</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $supplier->id == old('supplier_id') ? 'selected' : '' }}>
                                                    {{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('supplier_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('supplier_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-0">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Status') }}</label>
                        <!--begin::Label-->

                        <!--begin::Label-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                <input type="hidden" name="status" value="0">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="status"
                                    name="status" value="1" {{ old('status', 1) ? 'checked' : '' }} />
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->

                    <div class="row mt-5">
                        {{-- <div class="col-lg-12"> --}}
                        @if (!empty($bottom_menus) && $bottom_menus->app_menu_json != 'null')
                            @php
                                $bottom_menu_json = json_decode($bottom_menus->app_menu_json, true);
                            @endphp
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4>Bottom Menu</h4>
                                @foreach ($bottom_menu_json as $key => $bottom_menu)
                                    <div class="form-check mb-5">
                                        <input class="form-check-input mt-4" type="checkbox"
                                            value="{{ $bottom_menu['name'] }}" id="bottom_menu{{ $key }}"
                                            name="bottom_menu[]" />
                                        <label class="col-form-label" for="bottom_menu{{ $key }}"
                                            style="font-weight: bold; opacity:1">
                                            {{ $bottom_menu['name'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (!empty($sidebar_menus) && $sidebar_menus->app_menu_json != 'null')
                            @php
                                $sidebar_menu_json = json_decode($sidebar_menus->app_menu_json, true);
                            @endphp
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4>SideBar Menu</h4>
                                @foreach ($sidebar_menu_json as $keys => $sidebar_menu)
                                    <div class="form-check mb-5">
                                        <input class="form-check-input mt-4 main_menu" type="checkbox"
                                            value="{{ $sidebar_menu['title'] }}" data-attr_name="sidebar_menu"
                                            data-menu_value="{{ $keys }}"
                                            id="sidebar_menu{{ $keys }}" name="sidebar_menu[]" />
                                        <label class="col-form-label" for="sidebar_menu{{ $keys }}"
                                            style="font-weight: bold; opacity:1">
                                            {{ @$sidebar_menu['title'] }}
                                        </label>
                                    </div>
                                    @if (is_array($sidebar_menu['route']) && count($sidebar_menu['route']) > 0)
                                        @foreach ($sidebar_menu['route'] as $key2 => $submenu)
                                            <div class="form-check mb-5" style="margin-left:50px;">
                                                <input
                                                    class="form-check-input mt-4 sub_menu sub_menu{{ $keys }}"
                                                    type="checkbox" value="{{ $submenu['title'] }}"
                                                    data-attr_value="{{ $keys }}"
                                                    id="sidebar_sub_menu{{ $keys . $key2 }}" name="sub_menu[]" />
                                                <label class="col-form-label"
                                                    for="sidebar_sub_menu{{ $keys . $key2 }}"
                                                    style="font-weight: bold; opacity:1">
                                                    {{ @$submenu['title'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.app-menu-mapping.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @include('pages.setting.app.menu_map.script')
</x-default-layout>
