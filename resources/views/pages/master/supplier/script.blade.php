<script>
    $(function() {
        $('#amount-type-dropdown').on('change', function() {
            var amount_type = $(this).find(':selected').val();
            if (amount_type == 1) {
                $('.amountDiv').removeClass('d-none');
                $('#amount').attr('required', false);

                $('#percentage').val('');
                $('.percentageDiv').addClass('d-none');
                $('#percentage').attr('required', false);
            } else {
                $('.amountDiv').addClass('d-none');
                $('#amount').attr('required', false);
                $('#amount').val('');

                $('.percentageDiv').removeClass('d-none');
                $('#percentage').attr('required', false);
            }
        }).trigger('change');
    })
</script>
