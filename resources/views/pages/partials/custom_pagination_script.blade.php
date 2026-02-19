<script>
    $('#paginate_dropdown').on('change', function() {
        var paginateVal = $(this).val();
        $("<input type='hidden' name='paginate' id='paginateVal' value='"+paginateVal+"'/>").appendTo("#filterForm");
        $('#filterForm').submit();
    });
</script>
