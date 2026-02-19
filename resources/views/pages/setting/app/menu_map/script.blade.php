@section('scripts')
    <script>
       
        $(document).ready(function() {
            $('#type').change(function() {
                var type = $(this).val();
                console.log("typetype");
                console.log(type);
                if (type === '1') {
                    $('.employee_div').show();
                    $('.supplier_div').hide();
                } else if (type === '2') {
                    $('.employee_div').hide();
                    $('.supplier_div').show();
                } else {
                    $('.employee_div').hide();
                    $('.supplier_div').hide();
                }
            }).trigger('change');
        });
    </script>
@endsection
