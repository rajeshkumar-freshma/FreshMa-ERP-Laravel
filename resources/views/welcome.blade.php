<x-system-layout>

    <!--begin::Wrapper-->
    <div class="card card-flush w-lg-650px py-5">
        <div class="card-body py-15 py-lg-20">
            <!--begin::Title-->
            <img alt="Logo" src="{{ image('logos/fav.png') }}" class="h-60px h-lg-75px" />
            <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">Welcome to FreshMa</h1>
            <!--end::Title-->

            <!--begin::Link-->
            <div class="mb-0">
                {{-- <a href="{{ route('admin.dashboard') }}" class="">Return Home</a> --}}
            </div>
            <!--end::Link-->
        </div>
    </div>
    <!--end::Wrapper-->


</x-system-layout>
<footer class="footer mt-auto py-3 bg-body-tertiary">
    <div class="container">
        <section class="footer-copyright border-top py-3 bg-light">
            <div class="container d-flex align-items-center">
                <div class="col-md-6">
                    <p class="mb-0"> © 2024 FreshMa, All rights Reserved by RRK RETAIL PVT LTD</p>
                </div>
                <div class="col-md-6">

                    <p class="mb-0 align-items-end" style="float: right">
                        <a href="{{ route('privacyPolicies') }}"
                            class="btn btn-link">Privacy Policy</a>
                             | <a href="https://freshma.in/contact-us"
                            class="btn btn-link">Contact Us</a> </p>
                </div>


            </div>
        </section>
    </div>
</footer>
