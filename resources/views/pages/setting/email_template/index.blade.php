<x-default-layout>
    <script src="{!! url('assets/tinymce/js/tinymce.min.js') !!}"></script>
    @include('pages.partials.errors')
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'E-Mail Template Setup'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details">
            <!--begin::Form-->
            {{-- <form action="{{ route('admin.mail-setting.store', ['id' => @$templates->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('post') --}}
            <!--begin::Card body-->
            <div class="card-body border-top p-9 pb-0">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Email Template') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select class="form-control select_2" name="subject" id="subject">
                                        @foreach ($templates as $template)
                                            <option value="{{ $template->id }}">{{ $template->subject }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('subject'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>

            </div>
            <div id="appendEmailTemplate">

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Content-->

    {{-- @section('scripts')
    <script>
        // Retrieve the value of the subject select element
        document.addEventListener('DOMContentLoaded', function () {
            var subjectSelect = document.getElementById('subject');
            subjectSelect.addEventListener('change', function () {
                var id = subjectSelect.value;
                console.log("Subject select changed:", id);
            });
        });
    </script>
@endsection --}}
    <script src="{{ asset('admin_assets/js/index.js') }}"></script>
    <script src="{!! url('assets/tinymce/js/tinymce.min.js') !!}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    {{-- Your script --}}
    {{-- @section('scripts') --}}
    {{-- <script>
        var jq = jQuery.noConflict();
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Subject select changed:");

            // Function to fetch and update email template based on subject selection
            function updateEmailTemplate(id) {
                if (id) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                var response = JSON.parse(xhr.responseText);
                                document.getElementById('appendEmailTemplate').innerHTML = response.html;
                                // Initialize TinyMCE after updating the template content
                                initializeTinyMCE();
                                // Initialize Select2 for the status dropdown
                                var statusSelect = document.getElementById('status');
                                if (statusSelect) {
                                    new Select2(statusSelect);
                                }
                            } else {
                                console.error('Error fetching email template:', xhr.statusText);
                            }
                        }
                    };
                    xhr.open('GET', '/admin/setting/get-email-template/' + id, true);
                    xhr.send();
                }
            }

            // Initialize TinyMCE editor
            function initializeTinyMCE() {
                tinymce.remove(); // Remove existing instances
                tinymce.init(editor_config);
            }

            // Event listener for subject selection change
            document.getElementById('subject').addEventListener("change", function() {
                var id = this.value;
                console.log("Subject select changed:", id);
                updateEmailTemplate(id); // Update email template based on selected subject
            });

            // Initial call to update email template based on default subject selection
            var defaultSubjectId = document.getElementById('subject').value;
            updateEmailTemplate(defaultSubjectId);
        });
    </script> --}}
    {{-- @endsection --}}
    <script src="https://cdn.tiny.cloud/1/[my-api-key]/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            menubar: false,
        });
    </script>
    <script>
        console.log("Subject select changed:");
        var jq = jQuery.noConflict();
        // jq(document).ready(function() {
        //     // Function to fetch and update email template based on subject selection
        //     function updateEmailTemplate(id) {
        //         if (id) {
        //             jq.ajax({
        //                 type: 'GET',
        //                 url: '/admin/setting/get-email-template/' + id,
        //                 dataType: 'json',
        //                 success: function(response) {
        //                     jq('#appendEmailTemplate').html(response.html);
        //                     // Initialize TinyMCE after updating the template content
        //                     initializeTinyMCE();
        //                     // Initialize Select2 for the status dropdown
        //                     jq('#status').select2();
        //                 },
        //                 error: function(xhr, status, error) {
        //                     console.error('Error fetching email template:', error);
        //                 }
        //             });
        //         }
        //     }

        //     // Initialize TinyMCE editor
        //     function initializeTinyMCE() {
        //         tinymce.remove(); // Remove existing instances
        //         tinymce.init(editor_config);
        //     }

        //     // Event listener for subject selection change
        //     jq(document).on("change", "#subject", function() {
        //         var id = jq(this).val();
        //         console.log("Subject select changed:", id);
        //         updateEmailTemplate(id); // Update email template based on selected subject
        //     });

        //     // Initial call to update email template based on default subject selection
        //     var defaultSubjectId = jq("#subject").val();
        //     updateEmailTemplate(defaultSubjectId);
        // });
        jq(function() {
            jq('#subject').trigger('click');
        })

        jq('#subject').on('click change', function() {
            var id = jq('#subject').val();
            console.log(id);

            var url = "{{ route('admin.get_email_template', ':id') }}";
            url = url.replace(':id', id); // Replace the placeholder ':id' with the actual id

            console.log("url");
            console.log(url);

            if (id != null && typeof id != 'undefined') {
                jq.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'json', // Corrected 'datatype' to 'dataType'
                    success: function(response) {
                        jq('#appendEmailTemplate').html(response.html);
                        tinymce.remove();
                        tinymce.init(editor_config);
                        jq('#status').select2();
                    }
                });
            }
        });
    </script>

</x-default-layout>
