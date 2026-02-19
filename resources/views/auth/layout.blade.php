@extends('base.base')

@section('content')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10">
                        {{ $slot }}
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->
            </div>
            <!--end::Body-->
            <!--begin::Aside-->
            @php
                $main_image = \Storage::disk('s3')->temporaryUrl("media/logos/main_logo.png", '+2 minutes');
            @endphp
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset(theme()->getMediaUrlPath() . 'misc/auth-bg.png') }})">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center py-15 px-5 px-md-15 w-100">
                    <!--begin::Logo-->
                    <a href="{{ route('admin.dashboard') }}" class="mb-12">
                        <img alt="Logo" src="{{ $main_image }}" class="h-75px">
                    </a>
                    <!--end::Logo-->
                    <!--begin::Image-->
                    <img class="mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="{{ asset(theme()->getMediaUrlPath() . 'misc/auth-screens.png') }}" alt="">
                    <!--end::Image-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
@endsection
