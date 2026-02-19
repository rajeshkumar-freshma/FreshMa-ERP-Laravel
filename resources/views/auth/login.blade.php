<x-auth-layout>


    <!--begin::Signin Form-->
    <form class="form w-100 " novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{ route('admin.dashboard') }}" action="{{ route('admin.login') }}">
    @csrf

    <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">FreshMa</h1>
            <!--end::Title-->
            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">Login to your account</div>
            <!--end::Subtitle=-->
        </div>
        <!--begin::Heading-->
        <div class="fv-row mb-8 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent" value="{{ old('email') }}" required autofocus>
            <!--end::Email-->
        </div>
        <!--end::Input group=-->
        <div class="fv-row mb-3 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
            <!--begin::Password-->
            <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" value="">
            <!--end::Password-->
        </div>
        <!--end::Input group=-->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-success">
                @include('partials.general._button-indicator', ['label' => __('Continue')])
            </button>
        </div>
        <!--end::Submit button-->

        <div></div>
    </form>
    <!--end::Signin Form-->

</x-auth-layout>
