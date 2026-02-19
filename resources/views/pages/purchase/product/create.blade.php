<x-default-layout>
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header py-2 border-0" >
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('New Purchase') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details" class="collapse show">
            <!--begin::Form-->
            <form id="item_details_form" class="form" method="POST" action="{{ route('admin.item-type.store') }}" enctype="multipart/form-data">
            @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_number" class="required form-label">{{ __('Purchase Order Number') }}</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="po_number" placeholder="PO-"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_order_date" class="required form-label">{{ __('Order Date') }}</label>
                                <div class="input-group">
                                    <input id="po_order_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="order_date"/>
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_expected_date" class="required form-label">{{ __('Expected Date') }}</label>
                                <div class="input-group">
                                    <input id="po_expected_date" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" name="expected_date"/>
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_warehouse" class="form-label">{{ __('Warehouse') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="po_warehouse" id="po_warehouse" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                            <option value="1">Chintadripet</option>
                                            {{-- @foreach(\App\Core\Data::getCurrencyList() as $key => $value)
                                                <option data-kt-flag="{{ $value['country']['flag'] }}" value="{{ $key }}" {{ $key == old('currency', $info->currency ?? '') ? 'selected' :'' }}><b>{{ $key }}</b>&nbsp;-&nbsp;{{ $value['name'] }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_status" class="required form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-copy fs-4"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="po_status" id="po_status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach(config('app.purchase_status') as $key => $value)
                                                <option data-kt-flag="{{ $key }}" value="{{ $key }}" {{ $key == old('po_status') ? 'selected' :'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--begin::Label-->
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="po_file" class="form-label">{{ __('Attachments') }}</label>
                                <input class="form-control form-control-sm" type="file" id="po_file" multiple>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--begin::Product Search-->
                    @include('pages.purchase.product.product_search')
                    <!--End::Product Search-->

                    <!--begin::Product Items-->
                    @include('pages.purchase.product.item')
                    <!--End::Product Items-->


                    <!--begin::Product Search-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="po_item" class="required form-label">{{ __('Product') }}</label>
                            </div>
                        </div>

                        <div class="col-md-6 bg-light rounded">
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Sub Total') }}</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <h3 class="subtotal">asd</h3>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                            </div>

                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Discount') }}</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <h3 class="discount">asd</h3>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                            </div>

                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total') }}</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <h3 class="total">as</h3>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="status" class=" form-label">{{ __('Remarks') }}</label>

                                <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30" rows="3">{{ old('remarks') }}</textarea>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.purchase.index')])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>
            $(function(){
                $(".fsh_flat_datepicker").flatpickr();

                $('#po_item').on('change', function() {
                    var product = $(this).find(':selected').text();
                    $.ajax({
                        type:'get',
                        url:"{{ route('admin.purchaseitem.render') }}",
                        data: { product : product},
                        success:function(res){
                            $('.appendData').append(res)
                        }
                    })
                })
            })
        </script>
    @endsection
</x-default-layout>
