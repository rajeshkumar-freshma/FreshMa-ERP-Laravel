<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Partnership Type',
            'menu_1_link' => route('admin.partnership-type.index'),
            'menu_2' => 'Add Partnership Type',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Partnership Type'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="partnership_type_details" class="collapse show">
            <!--begin::Form-->
            <form id="partnership_type_details_form" class="form" method="POST"
                action="{{ route('admin.partnership-type.store') }}" enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Partnership Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="partnership_name"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Partnership Name" value="{{ old('partnership_name') }}" required />
                                @if ($errors->has('partnership_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('partnership_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Partnership Percentage') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="number" name="partnership_percentage"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Partnership Percentage" value="{{ old('partnership_percentage') }}"
                                    min="0" max="100" step="any" required onkeypress="return /[0-9]/i.test(event.key)" />
                                @if ($errors->has('partnership_percentage'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('partnership_percentage') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

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
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.partnership-type.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
