{{-- <x-default-layout>

    <!--begin::Navbar-->
    @include('pages.master.supplier.user_details')
    <h1>Payment Transaction Details Report</h1>
    <!--begin::Col-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Table Widget 5-->
        <div class="card card-flush h-xl-100">
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Payment Transaction Details Report</span>
                </h3>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-100px">Transactions Number</th>
                            <th class="text-end pe-0 min-w-100px">Transactions Type</th>
                            <th class="text-end pe-0 min-w-150px">Type</th>
                            <th class="text-end pe-0 min-w-50px">Amount</th>
                            <th class="text-end pe-0 min-w-50px"> Transaction Date</th>
                            <th class="text-end pe-0 min-w-50px"> Action</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($purchases as $purchase)
                        @foreach ($purchase->purchase_order_transactions as $transaction)
                        <tr>
                            <!--begin::Item-->
                            <td>
                                {{ $transaction->transaction_number }}
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            @if ($transaction->transaction_type == 1)
                            <td class="text-end">Purchase</td>
                            @endif
                            @if ($transaction->type == 2)
                            <td class="text-end">Debit</td>
                            @endif
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">{{ $transaction->amount }}</td>
                            <!--end::Price-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">{{ $transaction->transaction_datetime }}</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span><a href="{{ route('admin.supplier.show', $users->id) }}"><i class='fas fa-eye' style='font-size:15px;color:green'></i></a></span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                    @if (isset($purchases) && count($purchases) > 0)
                    <div class="d-flex justify-content-between mx-0 row mt-1">
                        <div class="col-sm-12 col-md-6">
                            Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of
                            {{ $purchases->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-6 float-right">
                            {{ $purchases->withQueryString()->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    @endif
                    <!--end::Table body-->
                </table>
                <!--end::Table-->

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Table Widget 5-->

    </div>
    <!--end::Col-->
</x-default-layout> --}}
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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Payment Transaction</h1>
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
                            <h3 class="fw-bold m-0">Payment Transaction Details</h3>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Action-->
                        {{-- <a href="{{ route('admin.supplier.edit', $users->id) }}"
                            class="btn btn-sm btn-primary align-self-center" target="_blank">Edit Profile</a> --}}
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">Transactions Number</th>
                                    <th class="text-end pe-0 min-w-100px">Transactions Type</th>
                                    <th class="text-end pe-0 min-w-150px">Type</th>
                                    <th class="text-end pe-0 min-w-50px">Amount</th>
                                    <th class="text-end pe-0 min-w-50px"> Transaction Date</th>
                                    <th class="text-end pe-0 min-w-50px"> Action</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @foreach ($purchases as $purchase)
                                    @foreach ($purchase->purchase_order_transactions as $transaction)
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                {{ $transaction->transaction_number }}
                                            </td>
                                            <!--end::Item-->
                                            <!--begin::Product ID-->
                                            @if ($transaction->transaction_type == 1)
                                                <td class="text-end">Purchase</td>
                                            @endif
                                            @if ($transaction->type == 2)
                                                <td class="text-end">Debit</td>
                                            @endif
                                            <!--end::Date added-->
                                            <!--begin::Price-->
                                            <td class="text-end">{{ $transaction->amount }}</td>
                                            <!--end::Price-->
                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="58">
                                                <span
                                                    class="text-dark fw-bold">{{ $transaction->transaction_datetime }}</span>
                                            </td>
                                            <td class="text-end" data-order="58">
                                                <span><a href="{{ route('admin.supplier.show', $users->id) }}"><i
                                                            class='fas fa-eye'
                                                            style='font-size:15px;color:green'></i></a></span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            @if (isset($purchases) && count($purchases) > 0)
                                <div class="d-flex justify-content-between mx-0 row mt-1">
                                    <div class="col-sm-12 col-md-6">
                                        Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of
                                        {{ $purchases->total() }} entries
                                    </div>
                                    <div class="col-sm-12 col-md-6 float-right">
                                        {{ $purchases->withQueryString()->links('vendor.pagination.custom') }}
                                    </div>
                                </div>
                            @endif
                            <!--end::Table body-->
                        </table>
                        <!--end::Card body-->
                    </div>
                    <!--end::details View-->
                    @include('pages.partials.form_footer', [
                        'is_save' => false,
                        'back_url' => route('admin.supplier.index'),
                    ])
                </div>
                <!--end::Content container-->

            </div>
            <!--end::Content-->
        </div>
    </div>

    <!--end::Content wrapper-->
    <!--begin::Footer-->
</x-default-layout>
