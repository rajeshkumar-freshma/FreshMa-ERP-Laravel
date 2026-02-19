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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Account Overview</h1>
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

                @include('pages.master.supplier.user_details')
                <!--end::Navbar-->
                <!--begin::details View-->
                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Profile Details</h3>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Action-->
                        {{-- <a href="{{ route('admin.supplier.edit', $users->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit Profile</a> --}}
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users->first_name)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users->first_name}}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                            @endif
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Last Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users->last_name)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users->last_name }}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Company</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->company)
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{$users?->user_info?->company}}</span>
                            </div>
                            @else
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Contact Phone
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Phone number must be active"></i></label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users->phone_number)
                            <div class="col-lg-8 d-flex align-items-center">
                                <span class="fw-bold fs-6 text-gray-800 me-2">{{$users->phone_number}}</span>
                            </div>
                            @else
                            <div class="col-lg-8 d-flex align-items-center">
                                <span class="fw-bold fs-6 text-gray-800 me-2">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Email</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users->email)
                            <div class="col-lg-8">
                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{$users->email}}</a>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">-</a>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">User Name
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="User Name"></i></label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users->user_code)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users->user_code}}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Country</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $users?->user_info?->country?->name ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">State</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $users?->user_info?->state?->name ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">City</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $users?->user_info?->city?->name ?? '-' }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Address</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->address)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->address}}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Joined Date</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->joined_at)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{($users->user_info)->joined_at ? \Carbon\Carbon::parse($users?->user_info?->joined_at)->format('Y-m-d') : '' }}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Pan Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->pan_number)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->pan_number}}&nbsp;&nbsp;&nbsp;{!! commoncomponent()->attachment_view(@$users?->user_info?->pan_image_full_url) !!}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>
                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Aadhar Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->aadhar_number)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->aadhar_number}}&nbsp;&nbsp;&nbsp;{!! commoncomponent()->attachment_view(@$users?->user_info?->aadhar_image_full_url) !!}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>

                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">ESI Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->esi_number)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->esi_number}}&nbsp;&nbsp;&nbsp;{!! commoncomponent()->attachment_view(@$users?->user_info?->esi_image_full_url) !!}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>

                            </div>
                            @endif

                            <!--end::Col-->
                        </div>
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">PF Number</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            @if ($users?->user_info?->pf_number)
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->pf_number}}&nbsp;&nbsp;&nbsp;{!!commoncomponent()->attachment_view(@$users?->user_info?->pf_image_full_url) !!}</span>
                            </div>
                            @else
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">-</span>

                                @endif

                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-10">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Status</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                @if ($users->status ==1)

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
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Bank Details</h3>
                            </div>

                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Bank Name</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users?->user_info?->bank_name)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->bank_name}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Account Number</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users?->user_info?->account_number)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->account_number}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Name as per Bank Record</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users?->user_info?->name_as_per_record)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->name_as_per_record}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Branch Name</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users?->user_info?->branch_name)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->branch_name}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">IFSC Code</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users?->user_info?->ifsc_code)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users?->user_info?->ifsc_code}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800"></span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Bank Passbook</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users?->user_info?->bank_passbook_file_path)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{!! commoncomponent()->attachment_view(@$users?->user_info?->bank_passbook_image_full_url) !!}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Salary Details</h3>
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Salary Type</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users->salary_details->salary_type == 1)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">Per Day</span>
                                </div>
                                @elseif($users->salary_details->salary_type == 2)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">Per Week</span>
                                </div>
                                @elseif($users->salary_details->salary_type == 3)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">Month</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Type</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users->salary_details->amount_type == 1)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">Fixed</span>
                                </div>
                                @elseif ($users->salary_details->amount_type == 2)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">Percentage</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif
                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Amount</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users->salary_details->amount)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users->salary_details->amount}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif

                                <!--end::Col-->
                            </div>
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Remark</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                @if ($users->salary_details->remarks)
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$users->salary_details->remarks}}</span>
                                </div>
                                @else
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">-</span>
                                </div>
                                @endif
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::details View-->
                    @include('pages.partials.form_footer', ['is_save' => false, 'back_url' => route('admin.supplier.index')])
                </div>
                <!--end::Content container-->

            </div>
            <!--end::Content-->
        </div>
    </div>

    <!--end::Content wrapper-->
    <!--begin::Footer-->
</x-default-layout>
