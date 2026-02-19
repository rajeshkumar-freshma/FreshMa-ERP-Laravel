@section('scripts')
    @include('pages.partials.date_picker')
    {{-- <script>
        function toggleAdditionalFields() {
            var additionalFields = document.getElementById('additionalFields');
            additionalFields.style.display = (additionalFields.style.display === 'none') ? 'block' : 'none';
        }
    </script> --}}
    <script>
        function toggleAdditionalFields() {
            // Show the modal
            $('#additionalFieldsModal').modal('show');

            // Populate modal content
            var modalBody = $('#additionalFieldsModal .modal-body');
            modalBody.html($('#additionalFields').html());
        }
    </script>
    <script>
        function leaveTypeStored() {
            var leaveTypeName = document.getElementById('leave_type_name').value;
            var leaveTypeStatus = document.getElementById('leave_type_status').value;

            if (leaveTypeName.trim() === '') {
                console.log('Leave type name is required');
                return;
            }

            var formData = new FormData();
            formData.append('leave_type_name', leaveTypeName);
            formData.append('leave_type_status', leaveTypeStatus);
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            formData.append('_token', csrfToken);

            $.ajax({
                url: "{{ route('admin.leave_type_stored') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 200) {
                        // Update the options in the leave_type dropdown
                        var leaveTypeSelect = document.getElementById('leave_type');
                        var newOption = document.createElement('option');
                        newOption.value = response.data.id;
                        newOption.text = response.data.name;
                        leaveTypeSelect.add(newOption);
                        // You may also want to select the newly added option
                        newOption.selected = true;

                        // Clear values and hide additional fields
                        document.getElementById('leave_type_name').value = '';
                        document.getElementById('leave_type_status').value = '1';
                        document.getElementById('additionalFields').style.display = 'none';

                        // Optionally, toggle additional fields visibility
                        toggleAdditionalFields();
                    }
                    // Handle success response here
                    console.log(response);
                },
                error: function(error) {
                    // Handle error response here
                    console.log(error);
                }
            });
        }
    </script>
@endsection
