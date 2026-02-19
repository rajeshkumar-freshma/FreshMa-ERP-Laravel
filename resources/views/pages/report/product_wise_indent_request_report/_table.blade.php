<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    @include('pages.partials.date_picker_script')
    <script>
        $(document).ready(function() {
            $(".fsh_flat_datepicker").flatpickr();

            var table = $('#productwiseindentrequestreport-table');
            console.log("table");
            console.log(table);
            table.on('preXhr.dt', function(e, settings, data) {
                data.product_id = $('#product_id').val();
                // data.from_date = $('#from_date').val();
                // data.to_date = $('#to_date').val();
            });

            $('.filter_button').on('click', function() {
                table.DataTable().ajax.reload();
                return false;
            })
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $("#submit").on('submit', function(e) {
                console.log("product entered");
                e.preventDefault(); // Prevent the default form submission
                // Collect form data
                console.log("product entered2");
                var formData = $(this).serialize(); // Use 'this' to refer to the form

                // AJAX request
                $.ajax({
                    url: "{{ route('admin.productwiseindentrequestdata') }}", // Specify your AJAX route
                    type: "POST", // or "POST" depending on your backend route configuration
                    data: {
                        formData: formData,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Handle the success response
                        console.log("product success");
                        console.log(response);
                        window.location.reload();
                        // Redirect to another script or function
                    },
                    error: function(error) {
                        console.log("product failed");
                        // Handle the error response
                        console.error(error);
                    }
                });
            });
        });
    </script> --}}
@endsection
