@section('scripts')
    {{-- <script>
        $('#state-dropdown').on('click change', function() {
            var url = "{{ route('admin.getCityByState') }}";
            var state_id = this.value;
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'state_id': state_id
                },
                success: (function(result) {
                    $("#city-dropdown").empty();
                    if (result.status == 200) {
                        $('#city-dropdown').append('<option value="">Select option</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city-dropdown").append('<option value="' + value.id + '">' +
                                value.name + '</option>');
                        })
                    }
                })
            });
        }).trigger('change');
        // Class definition
        var KTUsersUpdatePermission = function() {
            // Shared variables
            const element = document.getElementById('kt_modal_update_permission');
            const form = element.querySelector('#kt_modal_update_permission_form');
            const modal = new bootstrap.Modal(element);

            // Init add schedule modal
            var initUpdatePermission = () => {

                // Init form validation rules. For more info check the FormValidation plugin's official
                documentation: https: //formvalidation.io/
                    var validator = FormValidation.formValidation(
                        form, {
                            fields: {
                                'permission_name': {
                                    validators: {
                                        notEmpty: {
                                            message: 'Permission name is required'
                                        }
                                    }
                                },
                            },

                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                                })
                            }
                        }
                    );

                // Close button handler
                const closeButton = element.querySelector('[data-kt-permissions-modal-action="close"]');
                closeButton.addEventListener('click', e => {
                    e.preventDefault();

                    Swal.fire({
                        text: "Are you sure you would like to close?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, close it!",
                        cancelButtonText: "No, return",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function(result) {
                        if (result.value) {
                            modal.hide(); // Hide modal
                        }
                    });
                });

                // Cancel button handler
                const cancelButton = element.querySelector('[data-kt-permissions-modal-action="cancel"]');
                cancelButton.addEventListener('click', e => {
                    e.preventDefault();

                    Swal.fire({
                        text: "Are you sure you would like to cancel?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, cancel it!",
                        cancelButtonText: "No, return",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function(result) {
                        if (result.value) {
                            form.reset(); // Reset form
                            modal.hide(); // Hide modal
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: "Your form has not been cancelled!.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                }
                            });
                        }
                    });
                });

                // Submit button handler
                const submitButton = element.querySelector('[data-kt-permissions-modal-action="submit"]');
                submitButton.addEventListener('click', function(e) {
                    // Prevent default button action
                    e.preventDefault();

                    // Validate form before submit
                    if (validator) {
                        validator.validate().then(function(status) {
                            console.log('validated!');

                            if (status == 'Valid') {
                                // Show loading indication
                                submitButton.setAttribute('data-kt-indicator', 'on');

                                // Disable button to avoid multiple click
                                submitButton.disabled = true;

                                // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                setTimeout(function() {
                                    // Remove loading indication
                                    submitButton.removeAttribute('data-kt-indicator');

                                    // Enable button
                                    submitButton.disabled = false;

                                    // Show popup confirmation
                                    Swal.fire({
                                        text: "Form has been successfully submitted!",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result) {
                                        if (result.isConfirmed) {
                                            modal.hide();
                                        }
                                    });

                                    //form.submit(); // Submit form
                                }, 2000);
                            } else {
                                // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        });
                    }
                });
            }

            return {
                // Public functions
                init: function() {
                    initUpdatePermission();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function() {
            KTUsersUpdatePermission.init();
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            // Trigger AJAX call when edit button is clicked
            // $(document).on('click', '#edit-button', function(e) {
            function editApiKeys($id) {
                e.preventDefault();

                // // Get the API key ID from the button's data attribute
                // var apiKeyId = $(this).data('api-key-id');
                // console.log("apiKeyId:", apiKeyId);

                // Generate the URL using Laravel's route function
                var url = {{ route('admin.api-keys.edit', $id) }};
                console.log("url:", url);

                // Make your AJAX call here
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        // Show modal with fetched data
                        Swal.fire({
                            title: 'Edit Api Key',
                            html: response, // Show the data here
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            // If user confirms, submit the form
                            if (result.isConfirmed) {
                                $('#kt_modal_update_permission_form')
                                    .submit(); // Submit the form
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            }
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            $('#editButton').click(function() {
                // Display SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure you want to edit?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, edit it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit form via AJAX
                        $.ajax({
                            url: "{{ route('admin.api-keys.edit', $model->id) }}",
                            method: "GET",
                            success: function(response) {
                                // Handle success response if needed
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Your data has been edited.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle error response if needed
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while editing data.',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });
        });
    </script> --}}
@endsection
