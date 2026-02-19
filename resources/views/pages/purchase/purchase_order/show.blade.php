<x-default-layout>
    <div class="d-flex flex-column flex-column-fluid">
        <div class="card mb-5 mb-xl-10">
            @include('pages.purchase.purchase_order.purchase_nav')
        </div>
        <!--end::Navbar-->
        <!--begin::details View-->
        <div class="card mb-2" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Purchase Details</h3>

                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                {{-- <a href="{{ route('admin.purchase-order.edit', $purchase->id) }}" class="btn btn-sm btn-primary align-self-center" target="_blank">Edit</a> --}}
                <!--end::Action-->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="collapse show" id="purchaseDetailsCollapse">
                <div class="card-body p-9">

                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Purchase Order ID</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $purchase->purchase_order_number }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Warehouse Request ID</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $purchase->warehouse_request->request_code ?? '-' }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Warehouse</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $purchase->warehouse->name ?? '-' }}</span>
                        </div>
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Delivery Date</label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ $purchase->delivery_date ?? '-' }}</span>
                        </div> <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Attachments </label>
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{!! commoncomponent()->attachment_view($purchase->image_full_url) !!}</span>
                        </div> <!--end::Col-->
                    </div>

                </div>
            </div>
            <!--end::details View-->
        </div>
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header pt-7" data-bs-toggle="collapse" href="#purchaseOrderDetailsCollapse" role="button" aria-expanded="true" aria-controls="purchaseOrderDetailsCollapse">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Purchase Order Action Details</span>
                </h3>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="collapse " id="purchaseOrderDetailsCollapse">
                <div class="card-body">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-end pe-0 min-w-100px">Actioned User Type</th>
                                <th class="text-end pe-0 min-w-100px">Action_by_admin_id</th>
                                <th class="text-end pe-0 min-w-50px">Action Date</th>
                                <th class="text-end pe-0 min-w-150px">Action status</th>
                                {{-- <th class="text-end pe-0 min-w-50px">Delivery Date</th>
                                        <th class="text-end pe-0 min-w-50px">Created Date</th>
                                        <th class="text-end pe-0 min-w-25px">Price</th>
                                        <th class="text-end pe-0 min-w-25px">Action</th> --}}
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($purchase_action as $action)
                                <tr>
                                    <!--begin::Product ID-->
                                    @if ($action->actioned_user_type == 1)
                                        <td class="text-end">Admin</td>
                                    @else
                                        <td class="text-end">Supplier</td>
                                    @endif
                                    <!--end::Product ID-->
                                    <td class="text-end">{{ $action->created_by_details->first_name }}</td>
                                    <td class="text-end">
                                        <span class="badge py-3 px-4 fs-7 badge-light-primary">{{ $action->action_date }}</span>
                                    </td>
                                    <td class="text-end">@include('pages.partials.statuslabel', ['indent_status' => $action->status])</td>
                                </tr>
                            @endforeach


                        </tbody>
                        <!--end::Table body-->
                    </table>
                    @if (isset($purchase_action) && count($purchase_action) > 0)
                        <div class="d-flex justify-content-between mx-0 row mt-1">
                            <div class="col-sm-12 col-md-6">
                                Showing {{ $purchase_action->firstItem() }} to {{ $purchase_action->lastItem() }} of
                                {{ $purchase_action->total() }} entries
                            </div>
                            <div class="col-sm-12 col-md-6 float-right">
                                {{ $purchase_action->withQueryString()->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    @endif
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content container-->
        @include('pages.partials.form_footer', ['show_reset' => false, 'show_save' => false, 'is_save' => false, 'back_url' => route('admin.purchase-order.index')])
    </div>
    </div>
</x-default-layout>
