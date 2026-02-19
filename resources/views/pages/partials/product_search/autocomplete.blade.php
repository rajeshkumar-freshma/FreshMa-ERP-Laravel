<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">
    var given_quantity_display = false;
    var taxanddiscountdisplay = false;
    var type_display = false;
    var unit_display = true;
    var expense_display = false;
    var commission_and_expense_display = false;
    var vendor_percentage = 0;

    @if (
        \Request::is(
            '*/customer-indent-request',
            '*/customer-indent-request/*',
            '*/warehouse-indent-request',
            '*/warehouse-indent-request/*',
            '*/order-request',
            '*/order-request/*',
            '*/purchase-order',
            '*/purchase-order/*',
            '*/customer-sales',
            '*/customer-sales/*',
            '*/sales-order',
            '*/sales-order/*',
            '*/store-sales',
            '*/store-sales/*',
            '*/sales-return',
            '*/sales-return/*',
            '*/purchase-return',
            '*/purchase-return/*'))
        var amountdisplay = true;
        var subtotaldisplay = true;
    @else
        var amountdisplay = false;
        var subtotaldisplay = false;
    @endif

    @if (
        \Request::is(
            '*/order-request',
            '*/order-request/*',
            '*/purchase-order',
            '*/purchase-order/*',
            '*/customer-sales',
            '*/customer-sales/*',
            '*/sales-order',
            '*/sales-order/*',
            '*/store-sales',
            '*/store-sales/*'))
        var given_quantity_display = true;
    @endif

    @if (
        \Request::is(
            '*/order-request',
            '*/order-request/*',
            '*/purchase-order',
            '*/purchase-order/*',
            '*/customer-sales',
            '*/customer-sales/*',
            '*/sales-order',
            '*/sales-order/*',
            '*/store-sales',
            '*/store-sales/*'))
        var taxanddiscountdisplay = true;
    @endif

    @if (
        \Request::is(
            '*/purchase-order',
            '*/purchase-order/*',
            '*/customer-sales',
            '*/customer-sales/*',
            '*/sales-order',
            '*/sales-order/*',
            '*/store-sales',
            '*/store-sales/*',
            '*/product-transfer',
            '*/product-transfer/*'))
        var expense_display = true;
    @endif

    @if (\Request::is('*/adjustment', '*/adjustment/*'))
        var unit_display = false;
        var type_display = true;
    @endif

    @if (
        \Request::is(
            '*/customer-sales',
            '*/customer-sales/*',
            '*/sales-order',
            '*/sales-order/*',
            '*/store-sales',
            '*/store-sales/*'))
        var commission_and_expense_display = true;
    @endif

    productRequire();

    function productRequire() {
        var classLoopCount = $('.product_row').length;
        if (classLoopCount == 0) {
            $('#product_name').attr('required', true);
        } else {
            $('#product_name').attr('required', false);
        }
    }


    $('input.typeahead').typeahead({
        source: function(query, process) {
            $.ajax({
                url: '{{ route('admin.autocomplete') }}',
                dataType: "json",
                type: "post",
                data: {
                    "name": $('#product_name').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    process($.map(response.data, function(item) {
                        return {
                            id: item.value,
                            name: item.label
                        }
                    }));
                },
            })
        },
        afterSelect: function(item) {
            $("#product_id").val(item.id);
            getProductDetails(item.id);
        }
    });

    function getProductDetails(value, unit_id = null, quantity = null) {
        vendor_percentage = $('#ir_vendor').find(':selected').attr('data-vendor_percentage') || 0;
        var is_editable = $('#ir_vendor').find(':selected').attr('data-editable') || 0;
        var classLoopCount = $('.product_row').length;

        $.ajax({
            url: '{{ route('admin.get_product_details') }}',
            type: "post",
            data: {
                "id": value,
                count: classLoopCount,
                amountdisplay: amountdisplay,
                subtotaldisplay: subtotaldisplay,
                unit_id: unit_id,
                quantity: quantity,
                given_quantity_display: given_quantity_display,
                taxanddiscountdisplay: taxanddiscountdisplay,
                expense_display: expense_display,
                commission_and_expense_display: commission_and_expense_display,
                type_display: type_display,
                unit_display: unit_display,
                vendor_percentage: vendor_percentage,
                is_editable: is_editable,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('.appendData').append(response);
                $('.select2-container').remove();
                $('.form-select').select2();
                $('#product_name').val("");
                commission_edit_enable();
                productRequire();
            },
        })
    }

    $('#store_indent_request').on('change', function() {
        var indent_request_id = $(this).val();
        var request_type = 'store_indent_requests';
        getIndentRequestProductDetails(indent_request_id, request_type);
    });

    $('#indent_request').on('change', function() {
        var indent_request_id = $(this).val();
        var request_type = 'vendor_indent_requests';
        getIndentRequestProductDetails(indent_request_id, request_type);
    });

    $('#warehouse_ir_id').on('change', function() {
        var indent_request_id = $(this).val();
        var request_type = 'warehouse_indent_requests';
        getIndentRequestProductDetails(indent_request_id, request_type);
    });

    $('#sales_order_id').on('change', function() {
        var sales_order_id = $(this).val();
        var request_type = "sales_order";
        getIndentRequestProductDetails(sales_order_id, request_type);
    });

    $('#purchase_order_id').on('change', function() {
        var purchase_order_id = $(this).val();
        var request_type = "purchase_order";
        getIndentRequestProductDetails(purchase_order_id, request_type);
    });

    function getSalesOrderProductDetails(sales_order_id, request_type) {
        vendor_percentage = $('#ir_vendor').find(':selected').attr('data-vendor_percentage') || 0;
        var is_editable = $('#ir_vendor').find(':selected').attr('data-editable') || 0;
        var classLoopCount = $('.product_row').length;

        $.ajax({
            url: '{{ route('admin.get_sales_order_details') }}',
            type: "post",
            data: {
                sales_order_id: sales_order_id,
                count: classLoopCount,
                request_type: request_type,
                amountdisplay: amountdisplay,
                subtotaldisplay: subtotaldisplay,
                given_quantity_display: given_quantity_display,
                taxanddiscountdisplay: taxanddiscountdisplay,
                expense_display: expense_display,
                commission_and_expense_display: commission_and_expense_display,
                type_display: type_display,
                unit_display: unit_display,
                vendor_percentage: vendor_percentage,
                is_editable: is_editable,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('.appendData').html(response);
                $('.select2-container').remove();
                $('.form-select').select2();
                $('#product_name').val("");

                $('.product_row').each(function(key, index) {
                    subTotalCalculation(key);
                })

                commission_edit_enable();
                productRequire();
            }
        });
    }

    function getIndentRequestProductDetails(indent_request_id, request_type) {
        vendor_percentage = $('#ir_vendor').find(':selected').attr('data-vendor_percentage') || 0;
        var is_editable = $('#ir_vendor').find(':selected').attr('data-editable') || 0;
        var classLoopCount = $('.product_row').length;

        $.ajax({
            url: '{{ route('admin.get_indent_request_product_details') }}',
            type: "post",
            data: {
                indent_request_id: indent_request_id,
                count: classLoopCount,
                request_type: request_type,
                amountdisplay: amountdisplay,
                subtotaldisplay: subtotaldisplay,
                given_quantity_display: given_quantity_display,
                taxanddiscountdisplay: taxanddiscountdisplay,
                expense_display: expense_display,
                commission_and_expense_display: commission_and_expense_display,
                type_display: type_display,
                unit_display: unit_display,
                vendor_percentage: vendor_percentage,
                is_editable: is_editable,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('.appendData').html(response);
                $('.select2-container').remove();
                $('.form-select').select2();
                $('#product_name').val("");

                $('.product_row').each(function(key, index) {
                    subTotalCalculation(key);
                })

                commission_edit_enable();
                productRequire();
            }
        });
        
    }

    $('body').on('click', '.remove_item', function() {
        var data_product_id = $(this).attr('data-id');
        $('.product_checkbox' + data_product_id).attr('checked', false);
        $(this).parent().parent().remove();
        $('.product_row').each(function(key, index) {
            subTotalCalculation(key);
        })
        productRequire();
    })
</script>
