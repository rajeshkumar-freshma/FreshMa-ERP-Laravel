<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Supplier Bulk Payment',
            'menu_1_link' => route('admin.supplier-bulk-payment.index'),
            'menu_2' => 'Edit Supplier Bulk Payment',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Supplier Payment'])
        <!--begin::Card header-->
    </div>

    <!--begin::Content-->
    <div id="item_details" class="collapse show">
        <!--begin::Form-->
        <form id="item_details_form" class="form" method="POST"
            action="{{ route('admin.supplier-bulk-payment.update', @$purchase_order_multi_transactions->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <!--begin::Card body-->
            <div class="card">
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="supplier_id" class="form-label">{{ __('Supplier') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="supplier_id" id="supplier_id"
                                            aria-label="{{ __('Select Supplier') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Supplier..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Supplier..') }}</option>
                                            @foreach (@$supplier as $key => $supplier_details)
                                                <option data-kt-flag="{{ $supplier_details->id }}"
                                                    value="{{ $supplier_details->id }}"
                                                    {{ $supplier_details->id == old('supplier_id', @$purchase_order_multi_transactions->supplier_id) ? 'selected' : '' }}>
                                                    {{ $supplier_details->first_name . ' - ' . $supplier_details->user_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('supplier_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('supplier_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="purchase_order_id" class="form-label">{{ __('Purchase Order') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        {{-- <select name="purchase_order_id[]" id="purchase_order_id"
                                            aria-label="{{ __('Select Purchase Order') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Purchase Order..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            multiple>
                                            <option value="">{{ __('Select Puchase Orders..') }}</option>
                                        </select> --}}
                                        <select name="purchase_order_id[]" id="purchase_order_id"
                                            aria-label="{{ __('Select Purchase Order') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Purchase Order..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            multiple required>
                                            <option value="">{{ __('Select Puchase Orders..') }}</option>
                                            @foreach (@$purchase_orders as $purchase_order)
                                                <option value="{{ $purchase_order->id }}" @selected(in_array($purchase_order->id, @$purchase_order_ids))>
                                                    {{ $purchase_order->purchase_order_number }}
                                                    {{-- {{ $purchase_order->purchase_order_number }} - {{ $purchase_order->status }} --}}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                @if ($errors->has('purchase_order_id'))
                                    <span class="form-select form-select-sm form-select-solid" role="alert">
                                        <strong>{{ $errors->first('purchase_order_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="transaction_date"
                                    class="required form-label">{{ __('Transaction Date') }}</label>
                                <div class="input-group">
                                    <input id="transaction_date" type="text"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                        name="transaction_date"
                                        value="{{ old('transaction_date', @$purchase_order_multi_transactions->transaction_date) }}"
                                        required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('transaction_date'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="paid_amount" class="form-label">{{ __('Total Paid Amount') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-rupee-sign"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input name="paid_amount" id="paid_amount" type="text"
                                            value="{{ old('paid_amount') }}" aria-label="{{ __('Paid Amount') }}"
                                            data-placeholder="{{ __('Paid Amount..') }}"
                                            class="form-control form-control-sm form-control-solid">
                                        </input>
                                    </div>
                                </div>
                                @if ($errors->has('paid_amount'))
                                    <span class="form-select form-select-sm form-select-solid" role="alert">
                                        <strong>{{ $errors->first('paid_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="pending_amount" class="form-label">{{ __('Pending Amount') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-rupee-sign"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input name="pending_amount" id="pending_amount" type="text"
                                            value="{{ old('pending_amount') }}"
                                            aria-label="{{ __('Pending Amount') }}"
                                            data-placeholder="{{ __('Pending Amount..') }}"
                                            class="form-control form-control-sm form-control-solid">
                                        </input>
                                    </div>
                                </div>
                                @if ($errors->has('pending_amount'))
                                    <span class="form-select form-select-sm form-select-solid" role="alert">
                                        <strong>{{ $errors->first('pending_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="text" name="edit_page" value="1" hidden>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="amount"
                                    class="form-label required">{{ __('Freshma Need To Pay') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-rupee-sign"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input name="amount" id="amount" type="text"
                                            aria-label="{{ __('Select Amount') }}"
                                            value="{{ old('amount', @$purchase_order_multi_transactions->amount) }}"
                                            data-placeholder="{{ __('Select Amount..') }}"
                                            class="form-control form-control-sm form-control-solid" required>
                                        </input>
                                    </div>
                                </div>
                                @if ($errors->has('amount'))
                                    <span class="form-select form-select-sm form-select-solid" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="user_advance_amount"
                                    class="form-label">{{ __('User Advance Amount') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-rupee-sign"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <input name="user_advance_amount" id="user_advance_amount" type="text"
                                            value="{{ old('user_advance_amount') }}"
                                            aria-label="{{ __('User Advance Amount') }}"
                                            data-placeholder="{{ __('User Advance Amount..') }}"
                                            class="form-control form-control-sm form-control-solid" readonly>
                                        </input>
                                    </div>
                                </div>
                                @if ($errors->has('user_advance_amount'))
                                    <span class="form-select form-select-sm form-select-solid" role="alert">
                                        <strong>{{ $errors->first('user_advance_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="advance_amount_included"
                                    class="form-label">{{ __('Advance Amount Include') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="advance_amount_included" id="advance_amount_included"
                                            aria-label="{{ __('Select Advance Amount Include') }}"
                                            data-control="select2"
                                            data-placeholder="{{ __('Select Advance Amount Include..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Advance Amount Include..') }}
                                            </option>
                                            @foreach (config('app.advance_amount_included') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('advance_amount_included', @$purchase_order_multi_transactions->advance_amount_included) ? 'selected' : '' }}>
                                                    {{ $value['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('advance_amount_included'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('advance_amount_included') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-6">
                            <!--begin::Label-->
                            <div class="col-md-12">
                                <div class="mb-5">
                                    <label for="status" class="form-label">{{ __('Remarks') }}</label>

                                    <textarea class="form-control form-control-sm form-control-solid" name="remarks" id="remarks" cols="30"
                                        rows="3">{{ old('remarks', @$purchase_order_multi_transactions->remarks) }}</textarea>
                                </div>
                            </div>
                            <!--begin::Label-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
            </div>
            <!--end::Card body-->

            <!--begin::Actions-->
            @include('pages.partials.form_footer', [
                'is_save' => true,
                'back_url' => route('admin.supplier-bulk-payment.index'),
            ])
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
    <!--end::Basic info-->

    @section('scripts')
        <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
        <script>
            $(document).ready(function() {
                $(function() {
                    $(".fsh_flat_datepicker").flatpickr();

                    $('#supplier_id').on('change', function() {
                        var supplier_id = $(this).find(':selected').val();
                        console.log("supplier_id");
                        console.log(supplier_id);

                        $.ajax({
                            type: 'get',
                            url: "{{ route('admin.supplier_purchase_orders_edit_data_get') }}",
                            data: {
                                supplier_id: supplier_id
                            },
                            success: function(data) {
                                console.log("data");
                                console.log(data);
                                $("#purchase_order_id").empty();
                                var totalPaidAmount = 0;

                                if (data.status == 200) {
                                    // Access the 'purchase_orders' key in the response
                                    $.each(data.data.purchase_orders, function(index,
                                        purchaseOrder) {
                                        console.log("purchaseOrder");
                                        console.log(purchaseOrder);

                                        $("#purchase_order_id").append(
                                            '<option value="' + purchaseOrder
                                            .id + '">' + purchaseOrder
                                            .purchase_order_number
                                            // +' ' +'-' +purchaseOrder.status
                                            +
                                            '</option>'
                                        );

                                        // Listen for the change event on the purchase_order_id select element
                                        $('#purchase_order_id').on('change',
                                            function() {
                                                // Reset totalPaidAmount before recalculating
                                                totalPaidAmount = 0;
                                                totalPendingAmount = 0;

                                                var selectedPurchaseOrders = $(
                                                    this).val();
                                                if (selectedPurchaseOrders) {
                                                    $.each(selectedPurchaseOrders,
                                                        function(
                                                            index,
                                                            purchaseOrderId
                                                        ) {
                                                            var purchaseOrder =
                                                                data
                                                                .data
                                                                .purchase_orders
                                                                .find(
                                                                    function(
                                                                        order
                                                                    ) {
                                                                        return order
                                                                            .id ==
                                                                            purchaseOrderId;

                                                                    });
                                                            console.log(
                                                                "purchaseOrdertotal"
                                                            );
                                                            console.log(
                                                                purchaseOrder
                                                                .total);
                                                            if (purchaseOrder &&
                                                                purchaseOrder
                                                                .purchase_order_transactions &&
                                                                Array
                                                                .isArray(
                                                                    purchaseOrder
                                                                    .purchase_order_transactions
                                                                )) {
                                                                $.each(purchaseOrder
                                                                    .purchase_order_transactions,
                                                                    function(
                                                                        index,
                                                                        transaction
                                                                    ) {
                                                                        totalPaidAmount
                                                                            +=
                                                                            parseFloat(
                                                                                transaction
                                                                                .amount
                                                                            );
                                                                        // totalPendingAmount+= parseFloat(purchaseOrder.amount)
                                                                        // parseFloat(purchaseOrderId.total)-=totalPendingAmount;
                                                                    });
                                                            }
                                                            // Accumulate the total amount from purchase_orders table
                                                            totalPendingAmount
                                                                +=
                                                                parseFloat(
                                                                    purchaseOrder
                                                                    .total);
                                                        });
                                                }

                                                // Update the 'paid_amount' input field
                                                $('#paid_amount').val(
                                                    totalPaidAmount
                                                    .toFixed(2));
                                                var totalPendingAmountAfterPayment =
                                                    totalPendingAmount -
                                                    totalPaidAmount;
                                                $('#pending_amount').val(
                                                    totalPendingAmountAfterPayment
                                                    .toFixed(2));
                                                if (totalPendingAmountAfterPayment ==
                                                    0) {
                                                    $('#amount').prop(
                                                        'disabled', true);
                                                }

                                            });
                                    });

                                    // After appending all options, select options based on stored purchase_order_ids
                                    var storedPurchaseOrderIDs = {!! json_encode(@$purchase_order_ids) !!};
                                    if (storedPurchaseOrderIDs && storedPurchaseOrderIDs
                                        .length > 0) {
                                        $("#purchase_order_id").val(storedPurchaseOrderIDs);
                                        $("#purchase_order_id").trigger('change');
                                    }
                                    $('#supplier_id').on('change', function() {
                                        updateAdvanceAmount();
                                    });

                                    // Function to update user advance amount
                                    function updateAdvanceAmount() {
                                        var supplierId = $('#supplier_id').val();

                                        if (supplierId) {
                                            // Find the corresponding user_advance data in the response
                                            var userAdvance = data.data.user_advances.find(
                                                function(
                                                    advance) {
                                                    return advance.user_id ==
                                                        supplierId;
                                                });

                                            // Check if user_advance data exists
                                            if (userAdvance) {
                                                $("#user_advance_amount").val(userAdvance
                                                    .total_amount);
                                            } else {
                                                // Reset the value to 0 if no user_advance data is found
                                                $("#user_advance_amount").val(0);
                                            }
                                        } else {
                                            // Reset the value to 0 if no supplier is selected
                                            $("#user_advance_amount").val(0);
                                        }
                                    }

                                    // Initial call to update user advance amount based on the default selected supplier (if any)
                                    updateAdvanceAmount();

                                } else if (data.status == 400) {
                                    $("#purchase_order_id").append('<option value="' +
                                        '">' +
                                        "No Purchase Orders On this Supplier" +
                                        '</option>');
                                }
                            },
                            error: function() {
                                console.log("Error: No data");
                            }
                        });
                    }).trigger('change');
                });
            });
        </script>
    @endsection
</x-default-layout>
