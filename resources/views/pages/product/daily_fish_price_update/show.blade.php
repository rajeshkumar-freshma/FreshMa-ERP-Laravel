<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        {{-- <div class="card mb-5 mb-xl-10">
            @include('pages.product.daily_fish_price_update.daily_fish_price_nav')
        </div> --}}
        <!--end::Navbar-->
        <!--begin::details View-->
        <div class="card mb-2" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Daily Fish Price Update Details</h3>

                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                {{-- <a href="{{ route('admin.fish-price-update.edit', @$daily_fish_price_dates->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit</a> --}}
                <!--end::Action-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show" id="dailyFishPriceDetailsCollapse">
                <div class="card-body p-9">

                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Product Name</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ @$daily_fish_price_dates->product_details->name }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Store</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ @$daily_fish_price_dates->store_details->store_name}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->

                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Price </label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$daily_fish_price_dates->price }}</span>
                        </div> <!--end::Col-->
                    </div>

                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Price Update On</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$daily_fish_price_dates->price_update_date }}</span>
                        </div> <!--end::Col-->
                    </div>

                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Created At</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$daily_fish_price_dates->created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::details View-->
        </div>
        <!--end::Content container-->
        @include('pages.partials.form_footer', ['show_reset' => false, 'show_save' => false, 'is_save' => false, 'back_url' => route('admin.fish-price-update.index')])
    </div>
    </div>
</x-default-layout>
