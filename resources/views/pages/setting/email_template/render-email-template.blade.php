<div id="item_details">
    <form method="POST" action="{{ route('admin.setting.emailTemplateUpdate') }}" id="store"
        enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="card-body border-top p-9 pt-0">
            <input type="hidden" name="id" value="{{ $template['id'] }}" />
            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Subject') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="input-group input-group-sm flex-nowrap">
                            <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <input type="text" name="subject" id="subject"
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Item name" value="{{ @$template['subject'] }}" required />
                            </div>
                        </div>
                        @if ($errors->has('subject'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Code') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="input-group input-group-sm flex-nowrap">
                            <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <input type="text" name="code" id="code" required
                                    class="form-control form-control-solid form-control-lg mb-3 mb-lg-0"
                                    placeholder="Item name" value="{{ @$template['code'] }}" disabled />
                            </div>
                        </div>
                        @if ($errors->has('code'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Body') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="input-group input-group-sm flex-nowrap">
                            <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <textarea class="form-control tinymce-textarea" style="margin-bottom:10px;" tinymce-editor" name="body"
                                    id="email-body">{{ @$template['body'] }}</textarea>
                            </div>
                        </div>
                        @if ($errors->has('body'))
                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Status') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="input-group input-group-sm flex-nowrap">
                            <span class="input-group-text border-0"><i class="fas fa-status"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                                        <input type="hidden" name="status" value="0">
                                        <input class="form-check-input w-45px h-30px" type="checkbox" id="status"
                                            name="status" value="1"
                                            {{ old('status', @$template['status']) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="status"></label>
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
                    <!--end::Row-->
                </div>

                <!--end::Col-->
            </div>
            <!--begin::Input group-->
            <div class="col-md-12">
                <div class="d-flex justify-content-end py-6">
                    <button type="submit" class="btn btn-dark m-r-5 m-b-5 ladda-button">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
