<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Store Overview</h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Navbar-->

                @include('pages.master.store.store_details')
                <!--end::Navbar-->
                <!--begin::details View-->
               <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Store Details</h3>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Action-->
                        {{-- <a href="{{ route('admin.customer.edit', $users->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit Profile</a> --}}
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Store Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->

                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->store_name ?? '-'}}</span>
                            </div>


                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Slug</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->slug ?? '-' }}</span>
                            </div>


                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Store Code</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ $stores->store_code ?? '-'}}</span>
                            </div>


                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Warehouse
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Phone number must be active"></i></label>
                            <!--end::Label-->
                            <!--begin::Col-->

                            <div class="col-lg-8 d-flex align-items-center">
                                <span class="fw-bold fs-6 text-gray-800 me-2">
                                    {{ $stores->warehouse->name}}
                                {{-- @foreach ($warehouses as $key => $warehouse)
                                        {{ $warehouse->name }}
                                @endforeach --}}
                                </span>
                            </div>

                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Gst Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $stores->gst_number ?? '-'}}</a>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Start Date
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="User Name"></i></label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$stores->start_date ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Phone Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->phone_number ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Email</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->email ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Address</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->address ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Country</label>
                            <!--end::Label-->
                            <!--begin::Col-->

                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$stores->country->name ?? '-'}}</span>
                            </div>

                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">City</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{($stores->city)->name ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">State</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{($stores->state)->name ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Pin Code</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->pincode ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Latitude</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->latitude ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Longitude</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->longitude ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Direction</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->direction ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Status</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $stores->status == 1 ? 'Active' : 'Deactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--end::details View-->
                    @include('pages.partials.form_footer', ['is_save' => false, 'back_url' => route('admin.supplier.index')])
                </div>
                <!--end::Content container-->

            </div>
            <!--end::Content-->
        </div>
    </div>
</x-default-layout>
