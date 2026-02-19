<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Denomination',
            'menu_1_link' => route('admin.denomination-type.index'),
            'menu_2' => 'Add Denomination',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Denomination'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details" class="collapse show">
            <!--begin::Form-->
            <form class="form" method="POST" action="{{ route('admin.denomination-type.store') }}">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="denomination_value" class="required form-label">{{ __('Denomination Value') }}</label>
                                <input type="number" name="denomination_value" value="{{ old('denomination_value') }}"
                                    class="form-control form-control-sm form-control-solid" id="denomination_value"
                                    placeholder="Enter Denomation Value" required />
                                @if ($errors->has('denomination_value'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('denomination_value') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <input type="text" name="description" value="{{ old('description') }}"
                                    class="form-control form-control-sm form-control-solid" id="description"
                                    placeholder="Enter Description(Thousand)" />
                                @if ($errors->has('description'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
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
                    'back_url' => route('admin.denomination-type.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
