<x-default-layout>

    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10">
        <!--begin::Col-->
        <div class="col mb-5 mb-xl-10">
            @include('partials/widgets/charts/_widget-1')
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-6 mb-md-6 mb-xl-6">
            <canvas id="kt_chartjs_1"></canvas>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->


    @section('scripts')
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script>
            // Create the chart instance
            // Create the chart instance for Sales and Purchase Orders
            var SalesOrdersDataMonth = <?= json_encode(@$sales_order['months']) ?>;
            var SalesOrdersDataValue = <?= json_encode(@$sales_order['count']) ?>;
            var PurchaseOrdersDataMonth = <?= json_encode(@$purchase_order['months']) ?>;
            var PurchaseOrdersDataValue = <?= json_encode(@$purchase_order['count']) ?>;

            console.log("SalesOrdersData");
            console.log(SalesOrdersDataMonth);
            console.log(SalesOrdersDataValue);

            console.log("PurchaseOrdersData");
            console.log(PurchaseOrdersDataMonth);
            console.log(PurchaseOrdersDataValue);

            var element = document.getElementById("kt_charts_widget_38_chart1");

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-900');
            var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');

            var options = {
                series: [{
                        name: 'Sales Orders',
                        data: <?php echo json_encode(@$sales_order['count']); ?>,
                    },
                    {
                        name: 'Purchase Orders',
                        data: <?php echo json_encode(@$purchase_order['count']); ?>,
                    }
                ],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['50%'],
                        borderRadius: 0,
                        dataLabels: {
                            position: "top" // top, center, bottom
                        },
                        startingShape: 'flat'
                    },
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '20px',
                    markers: {
                        radius: 12
                    },
                    itemMargin: {
                        horizontal: 15
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -28,
                    style: {
                        fontSize: '13px',
                        colors: [labelColor]
                    },
                    formatter: function(val) {
                        return val; // + "H";
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: <?php echo json_encode(@$sales_order['months']); ?>,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: KTUtil.getCssVariableValue('--bs-gray-500'),
                            fontSize: '13px'
                        }
                    },
                    crosshairs: {
                        fill: {
                            gradient: {
                                opacityFrom: 0,
                                opacityTo: 0
                            }
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: KTUtil.getCssVariableValue('--bs-gray-500'),
                            fontSize: '13px'
                        },
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function(val) {
                            return +val
                        }
                    }
                },
                colors: [KTUtil.getCssVariableValue('--bs-primary'), KTUtil.getCssVariableValue(
                    '--bs-info')],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            // Create the chart instance
            var chart = new ApexCharts(element, options);

            // Render the chart
            chart.render();
        </script>
        <script>
            // Create the chart instance for Income and Expenses
            var IncomeDataMonth = <?= json_encode(@$incomeexpense['months']) ?>;
            var IncomeDataValue = <?= json_encode(@$income['amount']) ?>;
            var ExpenseDataValue = <?= json_encode(@$expense['amount']) ?>;

            console.log("IncomeData");
            console.log(IncomeDataMonth);
            console.log(IncomeDataValue);

            console.log("ExpenseData");
            // console.log(ExpenseDataMonth);
            console.log(ExpenseDataValue);

            var element = document.getElementById("kt_charts_widget_38_chart2");

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-900');
            var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');

            var options = {
                series: [{
                        name: 'Total Income Amount',
                        data: <?php echo json_encode(@$income['amount']); ?>
                    },
                    {
                        name: 'Total Expenses Amount',
                        data: <?php echo json_encode(@$expense['amount']); ?>
                    }
                ],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['50%'],
                        borderRadius: 0,
                        dataLabels: {
                            position: "top" // top, center, bottom
                        },
                        startingShape: 'flat'
                    },
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '20px',
                    markers: {
                        radius: 12
                    },
                    itemMargin: {
                        horizontal: 15
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -28,
                    style: {
                        fontSize: '13px',
                        colors: [labelColor]
                    },
                    formatter: function(val) {
                        return val; // + "H";
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: <?php echo json_encode(@$sales_order['months']); ?>,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: KTUtil.getCssVariableValue('--bs-gray-500'),
                            fontSize: '13px'
                        }
                    },
                    crosshairs: {
                        fill: {
                            gradient: {
                                opacityFrom: 0,
                                opacityTo: 0
                            }
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: KTUtil.getCssVariableValue('--bs-gray-500'),
                            fontSize: '13px'
                        },
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function(val) {
                            return +val
                        }
                    }
                },
                colors: [KTUtil.getCssVariableValue('--bs-primary'), KTUtil.getCssVariableValue(
                    '--bs-info')],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            // Create the chart instance
            var chart = new ApexCharts(element, options);

            // Render the chart
            chart.render();
        </script>
        <script>
            var storeWiseSalesOrdersData = <?php echo json_encode(@$storeWiseSalesOrdersCount); ?>;
            console.log("storeWiseSalesOrdersData");
            console.log(storeWiseSalesOrdersData);

            var element = document.getElementById("kt_charts_widget_38_chart3");
            // element.val(ProductTransferData);

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');
            var baseColor = KTUtil.getCssVariableValue('--bs-danger');
            var lightColor = KTUtil.getCssVariableValue('--bs-danger');
            var chartInfo = element.getAttribute('data-kt-chart-info');
            var options = {
                series: [{
                    name: [chartInfo],
                    data: Object.values(storeWiseSalesOrdersData)
                }],
                chart: {
                    fontFamily: 'inherit',
                    type: 'area',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0,
                        stops: [0, 80, 100]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: Object.keys(storeWiseSalesOrdersData),
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    tickAmount: 6,
                    labels: {
                        rotate: 0,
                        rotateAlways: true,
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    },
                    crosshairs: {
                        position: 'front',
                        stroke: {
                            color: baseColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    min: 1,
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function(val) {
                            return val
                        }
                    }
                },
                colors: [lightColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    strokeColor: baseColor,
                    strokeWidth: 3
                }
            };
            // Create the chart instance
            var chart = new ApexCharts(element, options);

            // Render the chart
            chart.render();
        </script>
        <script>
            var branchwiseIncomeAndExpenseData = <?php echo json_encode(@$branchwiseIncomeAndExpenseData); ?>;
            console.log("branchwiseIncomeAndExpenseData");
            console.log(branchwiseIncomeAndExpenseData);
            // Convert object to an array
            var dataArray = Object.entries(branchwiseIncomeAndExpenseData).map(([storeName, data]) => ({
                storeName,
                ...data
            }));
            if (Array.isArray(dataArray)) {
                var element = document.getElementById("kt_charts_widget_38_chart5");
                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');
                var baseColorIncome = KTUtil.getCssVariableValue('--bs-success'); // Change this color as needed
                var baseColorExpense = KTUtil.getCssVariableValue('--bs-danger'); // Change this color as needed

                var categories = dataArray.map(data => data.storeName);

                var options = {
                    series: [{
                        name: 'Total Income',
                        data: dataArray.map(data => data.totalIncome)
                    }, {
                        name: 'Total Expense',
                        data: dataArray.map(data => data.totalExpense)
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'area',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        area: {
                            // You can customize the area plot options here
                            opacity: 0.5,
                            fillTo: 'origin'
                        }
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'right',
                        markers: {
                            radius: 12,
                            customHTML: function() {
                                return '<div style="width: 12px; height: 12px;"></div>';
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    fill: {
                        type: ["gradient", "solid"],
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0,
                            stops: [0, 80, 100]
                        }
                    },
                    xaxis: {
                        categories: categories,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        tickAmount: 6,
                        labels: {
                            rotate: 0,
                            rotateAlways: true,
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        min: 1,
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            },
                            formatter: function(val) {
                                return Math.round(val);
                            }
                        }
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    },
                    colors: [baseColorIncome, baseColorExpense],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    markers: {
                        strokeColor: baseColorIncome,
                        strokeWidth: 3
                    }
                };
                // Create the chart instance
                var chart = new ApexCharts(element, options);

                // Render the chart
                chart.render();
            } else {
                console.error("branchwiseIncomeAndExpenseData is not an array.");
            }
        </script>

        <script>
            var storeWiseProductTransferData = <?php echo json_encode(@$storeWiseProductTransferCount); ?>;
            console.log("storeWiseProductTransferData");
            console.log(storeWiseProductTransferData);

            var element = document.getElementById("kt_charts_widget_38_chart4");
            // element.val(ProductTransferData);

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-900');
            var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');

            var options = {
                series: [{
                    name: 'Product Transfer',
                    data: Object.values(storeWiseProductTransferData)
                }],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['28%'],
                        borderRadius: 5,
                        dataLabels: {
                            position: "top" // top, center, bottom
                        },
                        startingShape: 'flat'
                    },
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -28,
                    style: {
                        fontSize: '13px',
                        colors: [labelColor]
                    },
                    formatter: function(val) {
                        return val; // + "H";
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: Object.keys(storeWiseProductTransferData),
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: KTUtil.getCssVariableValue('--bs-gray-500'),
                            fontSize: '13px'
                        }
                    },
                    crosshairs: {
                        fill: {
                            gradient: {
                                opacityFrom: 0,
                                opacityTo: 0
                            }
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: KTUtil.getCssVariableValue('--bs-gray-500'),
                            fontSize: '13px'
                        },
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function(val) {
                            return +val
                        }
                    }
                },
                colors: [KTUtil.getCssVariableValue('--bs-primary'), KTUtil.getCssVariableValue(
                    '--bs-primary-light')],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };
            // Create the chart instance
            var chart = new ApexCharts(element, options);

            // Render the chart
            chart.render();
        </script>
        //
        <script>
            //     $(function() {
            //         var ctx = document.getElementById('kt_chartjs_1');
            //         var labels = [];
            //         @foreach ($payment_details as $key => $payment_detail)
            //             labels[{{ $key }}] = '{{ $payment_detail->category_name }}';
            //         @endforeach

            //         var values = [];
            //         @foreach ($payment_details as $keys => $payment_detail)
            //             values[{{ $keys }}] = '{{ $payment_detail->total_amount }}';
            //         @endforeach

            //         const data = {
            //             labels: labels,
            //             datasets: [{
            //                 axis: 'y',
            //                 label: 'Product wise sales report',
            //                 data: values,
            //                 fill: false,
            //                 backgroundColor: [
            //                     'rgba(255, 99, 132, 0.2)',
            //                     'rgba(255, 159, 64, 0.2)',

            //                     @@ - 118, 749 + 204,
            //                     38 @@ 'rgb(153, 102, 255)',
            //                     'rgb(201, 203, 207)'
            //                 ],
            //                 borderWidth: 1
            //             }]
            //         };

            //         // Chart config
            //         const config = {
            //             type: 'bar',
            //             data: data,
            //             options: {
            //                 plugins: {
            //                     title: {
            //                         display: false,
            //                     }
            //                 },
            //                 responsive: true,
            //                 interaction: {
            //                     intersect: false,
            //                 },
            //                 scales: {
            //                     x: {
            //                         stacked: true,
            //                         stacked: true
            //                     },
            //                     y: {
            //                         stacked: true
            //                     }
            //                 }
            //             }
            //         };

            //         // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            //         var myChart = new Chart(ctx, config);
            //     })
            //
        </script>
    @endsection
</x-default-layout>
