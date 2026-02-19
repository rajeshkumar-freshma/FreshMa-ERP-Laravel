<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Purchase credit Notes',
            'menu_1_link' => route('admin.purchase-credit.index'),
            'menu_2' => 'Edit Purchase credit Notes',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Purchase Order'])
        <!--begin::Card header-->
    </div>
    <!--begin::Content-->
    <!--begin::Form-->
    <form id="warehouse_indent_request_form" class="form" method="POST" action="{{ route('admin.purchase-credit.update', $indent_request->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="warehouse_indent_request">
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="purchase_order_number" class=" form-label">{{ __('Purchase Order Number') }}</label>
                                <h5>{{ $indent_request->purchase_order_number }}</h5>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="delivery_date" class=" form-label">{{ __('Delivery Date') }}</label>
                                <h5>{{ \Carbon\Carbon::parse($indent_request->delivery_date)->format(config('app.created_at_dateformat')) }}</h5>
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_warehouse" class="form-label">{{ __('Warehouse') }}</label>
                                <h5>{{ $indent_request->warehouse->name }}</h5>
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class=" form-label">{{ __('Status') }}</label>
                                <h5><span class="badge bg-{{ config('app.purchase_status')[$indent_request->status]['color'] }}">{{ config('app.purchase_status')[$indent_request->status]['name'] }}</span></h5>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class=" form-label">{{ __('Supplier') }}</label>
                                <h5>{{ $indent_request->supplier->name }}</h5>
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="ir_file" class="form-label">{{ __('Attachments') }} {!! commoncomponent()->attachment_view($indent_request->image_full_url) !!}</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--begin::Product Search-->
                    @include('pages.partials.product_search.index', ['loop_data' => old('products', $indent_request->warehouse_indent_product_details), 'main_data' => $indent_request, 'supplier' => $indent_request->supplier_id, 'is_view_page' => true])
                    <!--End::Product Search-->
                </div>
            </div>
            <!--end::Card body-->
            @if (old('transport_tracking.transport_type_id'))
                @include('pages.partials.transport_detail', ['transport_detail' => old('transport_tracking'), 'is_old_data' => 'old_value'])
                @php
                    $tracking_count = count(old('transport_tracking'));
                @endphp
            @else
                @include('pages.partials.transport_detail', ['transport_detail' => $indent_request->transport_details, 'is_old_data' => 'edit_value'])
                @php
                    $tracking_count = count($indent_request->transport_details);
                @endphp
            @endif

            @if (old('expense.expense_type_id'))
                @include('pages.partials.expense_detail', ['data' => old('expense'), 'is_old_data' => 'old_value'])
                @php
                    $expense_count = count(old('expense'));
                @endphp
            @else
                @include('pages.partials.expense_detail', ['data' => $indent_request->expense_details, 'is_old_data' => 'edit_value'])
                @php
                    $expense_count = count($indent_request->expense_details);
                @endphp
            @endif

            @if (old('payment_details.payment_type_id'))
                @include('pages.partials.payment_detail', ['payment_details' => old('payment_details'), 'is_old_data' => 'old_value'])
                @php
                    $payment_count = count(old('payment'));
                @endphp
            @else
                @include('pages.partials.payment_detail', ['payment_details' => [], 'is_old_data' => 'edit_value'])
                @php
                    $payment_count = count([]);
                @endphp
            @endif

            <!--begin::Actions-->
            @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.purchase-credit.index')])
            <!--end::Actions-->
        </div>
        <!--end::Content-->
    </form>
    <!--end::Form-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>

            $(function() {
                $(".datetime_picker").flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
                $('.product_checkbox').on('click', function() {
                    var product = $(this).attr('data-id');
                    var unit_id = $(this).attr('data-unit_id');
                    var quantity = $(this).attr('data-quantity');
                    if ($(this)[0].checked == true) {
                        getProductDetails(product, unit_id, quantity);
                    } else {
                        $('.current_row' + product).remove();
                    }
                })

                $('#po_item').on('change', function() {
                    var product = $(this).find(':selected').text();
                    $.ajax({
                        type: 'get',
                        url: "{{ route('admin.purchaseitem.render') }}",
                        data: {
                            product: product
                        },
                        success: function(res) {
                            $('.appendData').append(res)
                        }
                    })
                })
            })
        </script>
        @include('pages.partials.date_picker_script')
        @include('pages.partials.common_script')
        @include('pages.partials.product_search.autocomplete')
        @include('pages.partials.product_search.calculation_script')
        @include('pages.partials.expense_add_more')
        @include('pages.partials.transport_detail_addmore')
    @endsection
</x-default-layout>
