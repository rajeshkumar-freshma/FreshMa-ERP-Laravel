<?php

namespace App\Console\Commands;

use App\Jobs\PaySilpAutoGenerateJob;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollTemplate;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceDetails;
use App\Models\UserAdvance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PaySilpAutoGenerateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:pay-silp-auto-generate-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pay Silp generate by each employee at the Month first';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Paysill Auto generate by employees');
        PaySilpAutoGenerateJob::dispatch();


    }



}
