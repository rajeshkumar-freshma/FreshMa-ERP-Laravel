<?php

namespace App\Jobs;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollTemplate;
use App\Models\PayrollType;
use App\Models\StaffAttendanceDetails;
use App\Models\UserAdvance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class PaySilpAutoGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Paysilp Job Started');

            $lastMonth = Carbon::now()->subMonth();
            $lastMonthDaysCount = $lastMonth->copy()->endOfMonth()->day;
            $lastMonthDays = $lastMonth->format('n');
            $lastYear = $lastMonth->format('Y');

            // Fetch all active payroll templates
            $payrolltemplates = PayrollTemplate::where('status', 1)->get();

            foreach ($payrolltemplates as $payrolltemplate) {
                Log::info("payrolltemplate");
                Log::info($payrolltemplate);
                print_r($payrolltemplate);
                // Fetch employee attendance for leave days in the last month
                $employeeAttendanceLeave = StaffAttendanceDetails::where('staff_id', $payrolltemplate->employee_id)
                    ->whereMonth('in_time', $lastMonthDays)
                    ->whereYear('in_time', $lastYear)
                    ->where('is_present', 0)
                    ->count();
                $amount = UserAdvance::where('user_id', $payrolltemplate->employee_id)
                    ->where('status', 1)
                    ->where('type', 1)
                    ->value('amount');
                // Assuming $payrolltemplate->payroll_templates is the JSON column
                $payrolltype = PayrollType::all();
                $payrollData = json_decode($payrolltemplate->payroll_templates, true);

                // Ensure that $payrollData is an array and is not empty
                if (is_array($payrollData) && !empty($payrollData)) {
                    $status1Sum = 0;
                    $status0Sum = 0;

                    // Iterate through $payrollData
                    foreach ($payrollData as $item) {
                        // Find the corresponding payroll type in the $payrolltype collection
                        $payrollType = $payrolltype->where('id', $item['payroll_type_id'])->first();

                        // Check if the payroll type exists and has a valid status
                        if ($payrollType && in_array($payrollType->payroll_types, [0, 1])) {
                            $amount = $item['amount'];

                            // Check the status and sum amounts accordingly
                            if ($payrollType->payroll_types == 1) {
                                $status1Sum += $amount;
                            } elseif ($payrollType->payroll_types == 0) {
                                $status0Sum += $amount;
                            }
                        }
                    }
                    Log::info("payrollData");
                    // Log::info($payrollData);
                    Log::info("status1Sum");
                    Log::info($status1Sum);
                    Log::info("status0Sum");
                    Log::info($status0Sum);
                    // Calculate the total amount
                    $totalAmount = array_sum(array_column($payrollData, 'amount'));

                    // Calculate the deduction based on leave days
                    $leaveDeduction = ($totalAmount / $lastMonthDaysCount) * $employeeAttendanceLeave;

                    // Subtract the leave deduction from the total amount
                    $netSalary = $totalAmount - $leaveDeduction - $amount - $status0Sum;

                    $payroll = new Payroll();
                    $payroll->employee_id = $payrolltemplate->employee_id;
                    $payroll->gross_salary = $netSalary;
                    $payroll->month = $lastMonthDays;
                    $payroll->year = $lastYear;
                    $payroll->loss_of_pay_days = $employeeAttendanceLeave;
                    $payroll->no_of_working_days = $lastMonthDaysCount;
                    $payroll->status = $payrolltemplate->status;
                    $payroll->save();

                    foreach ($payrollData as $data) {
                        Log::info("data");
                        Log::info($data);
                        $payrollDetail = new PayrollDetail();
                        $payrollDetail->payroll_id = $payroll->id;
                        $payrollDetail->payroll_type_id = $data['payroll_type_id'];
                        $payrollDetail->amount = $data['amount'];
                        $payrollDetail->save();
                    }
                } else {
                    // Handle the case where $payrollData is empty or not an array
                }
            }
            Log::info('Paysilp Job completed');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }
}
