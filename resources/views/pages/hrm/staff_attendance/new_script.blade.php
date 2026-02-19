@section('scripts')
    @include('pages.partials.date_picker')

    <script>
        $(document).ready(function() {
            // Call getEmployees function when the page is ready
            getEmployees();

            $(document).on("change", "#store_id", function() {
                console.log("Store change event");
                let attendance_date = $(".fsh_flat_datepicker").val();
                getEmployees(attendance_date);
            });

            $(document).on("change", ".fsh_flat_datepicker", function() {
                console.log("date change event");
                let attendance_date = $(".fsh_flat_datepicker").val();
                let storeId = $("#store_id").val();
                if(storeId){
                    getEmployees(attendance_date);
                }

            });

            function getEmployees(attendance_date) {
                var storeId = $("#store_id").val();
                var staffAttendanceId = $("#staff_attendance_id").val();



                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.get_store_employees') }}",
                    data: {
                        storeId: storeId,
                        staffAttendanceId: staffAttendanceId,
                        attendance_date: attendance_date,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $("#employee_details_form .employee-fields").remove(); // Remove existing fields
                        var employeeFields = "";

                        if (data.status === 200 && Array.isArray(data.employees)) {
                            $.each(data.employees, function(index, employee) {
                                console.log('employeesid ', employee.id)
                                employeeFields += `
                            <tr>
                                <td>
                                    <strong>${employee.first_name} ${employee.last_name} - ${employee.user_code}</strong>
                                       <input type="text" name="employee_id[]" value="${employee.id}" hidden />
                                </td>
                                    <td class="col-md-3">
                                            <div class="flex-grow-1">
                                                <input type="datetime-local" name="in_datetime[${employee.id}]" class="form-control form-control-sm form-control-solid" id="in_datetime${employee.id}" value="${getAttendanceValue(employee.id, 'in_time', data.attendanceDetails)}" placeholder="Enter In Time" required />
                                            </div>
                                    </td>

                                    <td class="col-md-3">
                                        <div class="flex-grow-1">
                                            <input type="datetime-local" name="out_datetime[${employee.id}]" class="form-control form-control-sm form-control-solid" id="out_datetime${employee.id}" value="${getAttendanceValue(employee.id, 'out_time', data.attendanceDetails)}" placeholder="Enter Out Time" />
                                        </div>

                                    </td>
                                    ${renderAttendanceTypeRadios(employee.id, data.attendanceDetails, data.attendanceTypes)}
                            </tr>
                            `;
                            });

                            $(".employee_details_append").html(employeeFields);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Helper function to get attendance value for a specific employee and field
            function getAttendanceValue(employeeId, field, attendanceDetails) {
                if (attendanceDetails) {
                    var detail = attendanceDetails.find(detail => detail.staff_id == employeeId);
                    if (detail && detail[field]) {
                        return detail[field];
                    }
                }
                return '';
            }

            // Helper function to render attendance type radios
            function renderAttendanceTypeRadios(employeeId, attendanceDetails, attendanceTypes) {
                console.log('renderAttendanceTypeRad');
                console.log(employeeId);
                console.log(attendanceDetails);
                console.log(attendanceTypes);
                var radios = '';
                if (attendanceDetails && attendanceTypes) {
                    var detail = attendanceDetails.find(detail => detail.staff_id == employeeId);
                    console.log('employee_id', employeeId);
                    console.log('detail ', detail);
                    // console.log('detail.ispresent ' , detail.is_present)

                    if (detail && detail.is_present !== null && attendanceDetails.length > 0) {
                        attendanceTypes.forEach(function(type) {
                            radios += `
                             <td class="col-md-3">

                                    <input class="form-check-input" type="radio" name="attendance_type[${employeeId}]" value="${type.value}" id="attendance_type_${employeeId}_${type.value}" ${type.value == detail.is_present ? 'checked' : ''}>

                            </td>
                        `;
                        });
                    } else {
                        attendanceTypes.forEach(function(type) {
                            radios += `
                             <td class="col-md-3">

                                    <input class="form-check-input" type="radio" name="attendance_type[${employeeId}]" value="${type.value}" id="attendance_type_${employeeId}_${type.value}" >

                            </td>

                        `;
                        });
                    }
                }
                return radios;
            }

        });
    </script>
@endsection()
