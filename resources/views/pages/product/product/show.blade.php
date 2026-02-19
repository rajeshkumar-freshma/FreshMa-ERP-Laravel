<x-default-layout>
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Product Overview</h1>
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
                @include('pages.product.product.product_details')
                <!--end::Navbar-->
                <!--begin::details View-->
                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Product Details</h3>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Action-->
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit Profile</a>
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Product Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$product->name ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Category</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                @foreach ($product->product_category as $category)
                                <span class="fw-bold fs-6 text-gray-800">{{ $category->category->name . ',' ?? '-' }}<br></span>
                                @endforeach
                            </div>

                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">SKU Code</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$product->sku_code ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">HSN Code</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$product->hsn_code ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Description</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$product->product_description ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-10">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Status</label>
                            <!--begin::Label-->
                            <!--begin::Label-->
                            @if ($product->status ==1)

                            <div class="col-lg-8">
                                <span class="badge badge-success">Active</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="badge badge-success">In Active</span>
                            </div>
                            @endif

                            <!--begin::Label-->
                        </div>
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Inventory Details</h3>
                        </div>
                        <br>
                        <!--end::Card title-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Item Type</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$product->item_type->name ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Unit</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$product->unit->unit_name ?? '-'}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Tax Type</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">
                                    @if ($product->tax_type == 1)
                                    Inclusive
                                    @elseif ($product->tax_type == 2)
                                    Exclusive
                                    @else
                                    -
                                    @endif
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Tax Rate</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $product->tax_rate ? $product->tax_rate->tax_name . ' ' . $product->tax_rate->tax_rate : '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::details View-->
                @include('pages.partials.form_footer', ['is_save' => false, 'back_url' => route('admin.product.index')])
            </div>
            <!--end::Content container-->

        </div>
        <!--end::Content-->
    </div>
    </div>
    <!--end::Content wrapper-->
    <!--begin::Footer-->
</x-default-layout>
