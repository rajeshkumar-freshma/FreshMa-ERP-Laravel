@section('scripts')
    @include('pages.partials.date_picker')
    <script>
        function removeRow(button) {
            console.log(button)
            // Find the parent row and remove it
            var row = button.closest('.remove');
            row.remove();
        }
    </script>
    <script>
        $(document).ready(function() {
            // Call getEmployees function when the page is ready
            getEmployees();

            // Use event delegation for the change event
            $(document).on("change", "#employee_id", function() {
                console.log("Store change event");
                getEmployees();
            });

            function getEmployees() {
                var selectedOption = $("#employee_id option:selected");
                var employeeId = selectedOption.val();
                var payRollId = '{{ @$payroll->id }}';
                var userCode = selectedOption.data("user-code");
                $("#show_user_code").text("Employee ID: " + userCode);
                console.log("employeeId", employeeId);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.get_employee_pay_silp_previous_edit') }}",
                    data: {
                        employeeId: employeeId,
                        payRollId: payRollId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log("payrollData");
                        console.log(data);
                        if (data.status === 200) {
                            console.log("data.data");
                            console.log(data.data);
                            $('.earning_deduction_append').html(data.data);
                        }else{
                            $('.earning_deduction_append').html('');
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function employeeExistsInUserAdvancedTable(employeeId) {
                console.log("employeeExistsInUserAdvancedTable")
                // Use an AJAX request to check if the employee exists in the table
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.check_employee_exists_user_advanced') }}",
                    data: {
                        employeeId: employeeId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.amount) {
                            console.log("response.amount")
                            console.log(response.amount)
                            // If employee exists, get the amount
                            // getAmountFromUserAdvanced(employeeId);
                            console.log("exists advanced if")
                            // $('#amount5').val(response.amount);
                            $(`#amount${response.payrollTypeId}`).val(response.amount);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }




        });
    </script>
    <script>
        function addPayroll(type) {
            var employeeId = $("#employee_id").val();
            console.log("employeeId: " + employeeId + ", type: " + type);

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.add_payroll_earning') }}",
                data: {
                    employeeId: employeeId,
                    type: type,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    var earningContainer = $(
                        "#payrollEarningContainer"); // Update this to the correct container
                    var deductionContainer = $(
                        "#payrollDeductionContainer"); // Update this to the correct container

                    if (data.status === 200 && Array.isArray(data.data)) {
                        console.log(data.data)
                        console.log("data.payrollTypeMethods")
                        var selectDropdown = $('<select>', {
                            class: 'form-select form-select-sm',
                            name: 'selected_payroll_type'
                        });

                        $.each(data.data, function(index, method) {
                            console.log(method)
                            console.log("method")
                            // Create a unique identifier for each element
                            var uniqueId = "payroll_" + method.id;

                            if (data.type == 1) {
                                var option = `<div class="row mb-2 remove" id="${uniqueId}">
                                            <label class="col-md-3 col-form-label ${method.payroll_types == 1 ? 'text-success' : 'text-danger'}">
                                                ${method.name}
                                            </label>
                                            <input type="hidden" name="payroll_data[${method.id}][payroll_type_id]" value="${method.id}">
                                            <div class="col-md-5">
                                                <input name="payroll_data[${method.id}][amount]" type="text" class="form-control form-control-sm amount-input" placeholder="Enter Amount" id="amount${method.id}">
                                            </div>
                                            <div class="col-md-2">
                                                <span class="input-text border-0 text-danger" onclick="removeRow('${uniqueId}')">
                                                    <i class="fas fa-times fs-4"></i>
                                                </span>
                                            </div>
                                        </div>`;

                                // Append the option to the container
                                earningContainer.append(option);
                            } else {
                                var option = `<div class="row mb-2 remove" id="${uniqueId}">
                                            <label class="col-md-3 col-form-label ${method.payroll_types == 1 ? 'text-success' : 'text-danger'}">
                                                ${method.name}
                                            </label>
                                            <input type="hidden" name="payroll_data[${method.id}][payroll_type_id]" value="${method.id}">
                                            <div class="col-md-5">
                                                <input name="payroll_data[${method.id}][amount]" type="text" class="form-control form-control-sm amount-input" placeholder="Enter Amount" id="amount${method.id}">
                                            </div>
                                            <div class="col-md-2">
                                                <span class="input-text border-0 text-danger" onclick="removeRow('${uniqueId}')">
                                                    <i class="fas fa-times fs-4"></i>
                                                </span>
                                            </div>
                                        </div>`;

                                // Append the option to the container
                                deductionContainer.append(option);
                            }
                            // Create the selection option
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to remove a row
        function removeRow(id) {
            $("#" + id).remove();
        }
    </script>



    <script>
        $(document).ready(function() {
            // Call the function to calculate gross salary on page load
            calculateGrossSalary();

            // Use event delegation for the input change event
            $(document).on("input", ".amount-input", function() {
                // Call the function whenever the user changes the amounts
                calculateGrossSalary();
            });

            function calculateGrossSalary() {
                // Get all earning amounts
                var earningAmount = getAmount("payrollEarningContainer");
                console.log("earningAmount")
                console.log(earningAmount)
                // Get all deduction amounts
                var deductionAmount = getAmount("payrollDeductionContainer");
                console.log("deductionAmount")
                console.log(deductionAmount)
                // Calculate the gross salary
                var grossSalary = earningAmount - deductionAmount;

                // Update the gross salary label
                $("#grossSalaryLabel").text(grossSalary);

                // Update the hidden input value
                $("#grossSalaryInput").val(grossSalary);
            }

            function getAmount(containerId) {
                // Get the amount from the specified container
                return $("#" + containerId + " input.amount-input").toArray().reduce(function(sum, el) {
                    return sum + parseFloat(el.value) || 0;
                }, 0);
            }

            // You can also trigger the input event on page load
            $(".amount-input").trigger("input");
        });
    </script>

    <script>
        $(document).ready(function() {
            // Call the function on page load
            updateWorkingDays();

            function updateWorkingDays() {
                var selectedMonth = document.getElementsByName('payroll_month')[0].value;
                var selectedYear = document.getElementsByName('payroll_year')[0].value;

                // Use JavaScript to get the number of days in the selected month and year
                var daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

                // Update the "Number of Working Days" input field
                document.getElementById('NumberOfWorkingDays').value = daysInMonth;
            }

            // Attach the onchange event to the selects
            $('select[name="payroll_month"], select[name="payroll_year"]').change(function() {
                updateWorkingDays();
            });
        });
    </script>
    <script>
        function employeeExistsInUserAdvancedTable(employeeId) {
            console.log("employeeExistsInUserAdvancedTable")
            // Use an AJAX request to check if the employee exists in the table
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.check_employee_exists_user_advanced') }}",
                data: {
                    employeeId: employeeId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.amount && response.payrollTypeId !== null) {
                        console.log("response.amount")
                        console.log(response.amount)
                        // If employee exists, get the amount
                        // getAmountFromUserAdvanced(employeeId);
                        console.log("exists advanced if")
                        // $('#amount5').val(response.amount);
                        $(`#amount${response.payrollTypeId}`).val(response.amount);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
