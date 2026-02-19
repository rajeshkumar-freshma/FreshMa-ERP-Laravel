@section('scripts')
    <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
    <script>
        $(function() {
            $(".fsh_flat_datepicker").flatpickr();
        });
    </script>
    <script>
        // $(document).ready(function() {
        //     // Handle product selection change
        //     $('#products').trigger('change');
        //     $('#products').change(function() {
        //         var selectedProductId = $(this).val();

        //         // Check if a product is selected
        //         if (selectedProductId) {
        //             // Update the selected product name
        //             var selectedProductName = $('#products option:selected').text();
        //             $('#selected_product_name').text(selectedProductName);

        //             // Show the additional information section
        //             $('#additional_info_section').show();
        //         } else {
        //             // Hide the additional information section if no product is selected
        //             $('#additional_info_section').hide();
        //         }
        //     });

        //     // Handle remove option click
        //     $(document).on('click', '.remove-option', function() {
        //         // Clear the selected product and hide the additional information section
        //         $('#products').val('').trigger('change');
        //         $('#additional_info_section').hide();
        //     });
        // });
        $(document).ready(function() {
            var store_id = $("#store_id").val();
            console.log("store_id");
            console.log(store_id);
            $("#update_store_id").val(store_id);
            console.log($("#update_store_id").val());
            var stock_updated_on = $("#stock_updated_date").val();
            console.log("stock_updated_on");
            console.log(store_id);
            $("#stock_updated_on").val(stock_updated_on);
            $("#submit_type1").on('click', function() {
                $("#submit_type").val(1);
            });

            $("#submit_type2").on('click', function() {
                $("#submit_type").val(2);
            });

        });
    </script>
@endsection
