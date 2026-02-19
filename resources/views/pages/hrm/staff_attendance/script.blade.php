@section('scripts')
    @include('pages.partials.date_picker')
    {{-- <script>
        // Attach an event handler to the #store select element
        $(document).on("change", "#store_id", function() {
            console.log("Store change event");
            getEmployees();
        });

        function getEmployees() {
            var storeId = $("#store_id").val();
            console.log("getEmployees")
            console.log(storeId)

            // Make an Ajax call to get employees based on the selected store
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.get_store_employees') }}", // Use the named route
data: {
storeId: storeId,
'_token': '{{ csrf_token() }}'
},
success: function(data) {
// Clear existing options
$("#employee_id").empty();

// Add default option
$("#employee_id").append('<option value="">Select Employee</option>');

if (data.status === 200 && Array.isArray(data.employees)) {
// Iterate over the employees array
$.each(data.employees, function(index, employee) {
console.log(employee);

// Check if the employee ID matches the old value
var selected = ($.inArray(employee.id.toString(),
{!! json_encode(old('employee_id', [])) !!}) !== -1) ? 'selected' : '';

'';
$("#employee_id").append('<option value="' + employee.id + '" ' + selected +
                                '>' + employee.first_name + ' ' + employee.last_name + '</option>');
});
}
// Additional code as needed
},

error: function(xhr, status, error) {
console.error(xhr.responseText); // Log the error for debugging
}
});
}
</script> --}}
    <script>
        $(document).ready(function() {
            // Call getEmployees function when the page is ready
            getEmployees();

            $(document).on("change", "#store_id", function() {
                console.log("Store change event");
                getEmployees();
            });

            function getEmployees() {
                var storeId = $("#store_id").val();
                var staffAttendanceId = $("#staff_attendance_id").val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.get_store_employees') }}",
                    data: {
                        storeId: storeId,
                        staffAttendanceId: staffAttendanceId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $("#employee_details_form .employee-fields").remove(); // Remove existing fields
                        var employeeFields = "";

                        if (data.status === 200 && Array.isArray(data.employees)) {
                            $.each(data.employees, function(index, employee) {
                                employeeFields += `
                                <div>
                                    <strong>${employee.first_name} ${employee.last_name} - ${employee.user_code}</strong>
                                </div>
                                <div class="row mb-12 employee-fields">
                                    <div class="col-md-3">
                                        <label for="in_datetime${employee.id}" class="col-lg-4 col-md-6 col-sm-12 col-form-label required fw-bold fs-6">In Time</label>
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text border-0"><i class="fas fa-calendar"></i></span>
                                            <div class="flex-grow-1">
                                                <input type="datetime-local" name="in_datetime[${employee.id}]" class="form-control form-control-sm form-control-solid" id="in_datetime${employee.id}" value="${getAttendanceValue(employee.id, 'in_time', data.attendanceDetails)}" placeholder="Enter In Time" required />
                                            </div>
                                        </div>
                                    </div>
                                    <input type="text" name="employee_id[]" value="${employee.id}" hidden />
                                    <div class="col-md-3">
                                        <label for="out_datetime${employee.id}" class="col-lg-4 col-md-6 col-sm-12 col-form-label fw-bold fs-6">Out Time</label>
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text border-0"><i class="fas fa-calendar"></i></span>
                                            <div class="flex-grow-1">
                                                <input type="datetime-local" name="out_datetime[${employee.id}]" class="form-control form-control-sm form-control-solid" id="out_datetime${employee.id}" value="${getAttendanceValue(employee.id, 'out_time', data.attendanceDetails)}" placeholder="Enter Out Time" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-lg-4 col-md-6 col-sm-12 col-form-label required fw-bold fs-6">Attendance Type</label>
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <div class="flex-grow-1">
                                                <div class="d-flex">
                                                    ${renderAttendanceTypeRadios(employee.id, data.attendanceDetails, data.attendanceTypes)}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="attendance_type[${employeeId}]" value="${type.value}" id="attendance_type_${employeeId}_${type.value}" ${type.value == detail.is_present ? 'checked' : ''}>
                                <label class="form-check-label" for="attendance_type_${employeeId}_${type.value}">
                                    <span class="badge bg-${type.color}" style="font-size: 14px;">
                                        <i class="fas fa-${type.icon_name}"></i>
                                        ${type.name}
                                    </span>
                                </label>
                            </div>
                        `;
                        });
                    } else {
                        attendanceTypes.forEach(function(type) {
                            radios += `
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="attendance_type[${employeeId}]" value="${type.value}" id="attendance_type_${employeeId}_${type.value}" >
                                <label class="form-check-label" for="attendance_type_${employeeId}_${type.value}">
                                    <span class="badge bg-${type.color}" style="font-size: 14px;">
                                        <i class="fas fa-${type.icon_name}"></i>
                                        ${type.name}
                                    </span>
                                </label>
                            </div>
                        `;
                        });
                    }
                }
                return radios;
            }

        });
    </script>
@endsection()
