<!--begin::Table-->
@include('pages.partials._datatable-toolbar-template', [
    'tableId' => 'warehouse-table',
    'dataTable' => $dataTable
])
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        (function() {
            $(function() {
                $('body').on('click', '.statuschange', function() {
                    var warehouse_id = $(this).attr('data-warehouse_id');
                    var status_value = this.checked ? 1 : 0;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.warehouse.statuschange') }}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'warehouse_id': warehouse_id,
                            'status_value': status_value,
                        },
                        success: (function(result) {
                            alertMessage(result.message, result.status, 'status')
                        })
                    });
                })

                $('body').on('click', '.defaultwarehouse', function() {
                    var warehouse_id = $(this).attr('data-warehouse_id');
                    var value = this.checked ? 1 : 0;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.warehouse.defaultwarehouse.update') }}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'value': value,
                            'warehouse_id': warehouse_id,
                            'default': 1,
                        },
                        success: (function(result) {
                            alertMessage(result.message, result.status, 'default')
                        })
                    });
                });
            })


            function alertMessage(message, status, type) {
                if (status == 200) {
                    var titleData = "Success";
                    var display_icon = "success";
                } else {
                    var titleData = "Oops!";
                    var display_icon = "error";
                }
                Swal.fire({
                    title: titleData,
                    text: message,
                    icon: display_icon,
                    showCancelButton: false,
                    confirmButtonText: 'Okay!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var table = $('#warehouse-table');
                        table.DataTable().ajax.reload();
                        return false;
                    }
                });
            }
        })();
    </script>
@endsection
