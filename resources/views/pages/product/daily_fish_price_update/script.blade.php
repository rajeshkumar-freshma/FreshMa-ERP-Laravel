@section('scripts')
    <script src="{{ asset('assets/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
    <script>
        $(function() {
            $(".fsh_flat_datepicker").flatpickr();
        });
    </script>
    <script>
        $(document).ready(function() {
            var store_id = $("#store_id").val();
            console.log("store_id");
            console.log(store_id);
            $("#update_store_id").val(store_id);
            var price_updated_on = $("#price_updated_date").val();
            console.log("price_updated_on");
            console.log(price_updated_on);
            $("#price_updated_on").val(price_updated_on);
        });
    </script>
@endsection
