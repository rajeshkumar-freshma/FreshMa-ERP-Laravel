<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Tax Rate',
            'menu_1_link' => route('admin.tax-rate.index'),
            'menu_2' => 'Edit Tax Rate',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Tax Rate'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="tax_rate" class="collapse show">
            <!--begin::Form-->
            <form id="tax_rate_form" class="form" method="POST"
                action="{{ route('admin.tax-rate.update', $tax_rate->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Tax Name') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="tax_name"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Tax Name" value="{{ old('tax_name', $tax_rate->tax_name) }}"
                                    required />
                                @if ($errors->has('tax_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tax_name') }}</strong>
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
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Tax Rate') }}</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <input type="text" name="tax_rate"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Tax Rate" value="{{ old('tax_rate', $tax_rate->tax_rate) }}"
                                    required />
                                @if ($errors->has('tax_rate'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tax_rate') }}</strong>
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
                                    name="status" value="1"
                                    {{ old('status', $tax_rate->status) ? 'checked' : '' }} />
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
                    'is_save' => false,
                    'back_url' => route('admin.tax-rate.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
