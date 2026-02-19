<?php

namespace App\Jobs;

use App\Core\CommonComponent;
use App\Exports\StoreStockExport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class StoreStockExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $from_date, $to_date, $store_id;

    public function __construct($from_date, $to_date, $store_id)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->store_id = $store_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("entered job");

        $results = new StoreStockExport($this->from_date,$this->to_date, $this->store_id);

        if (ENV('APP_ENV') == 'production') {
            $file_path = 'reports/product_stock';
            $file_name = 'user-' . date('Y-m-d-His') . '.xlsx';
            $filename = 'reports/product_stock/user-' . date('Y-m-d-His') . '.xlsx';
            $file = Excel::store($results, $filename, 's3');
        } else {
            $filename = 'reports/product_stock/user-' . date('Y-m-d-His') . '.xlsx';
            $file = Excel::store($results, $filename, 'admin');
            $filename = 'uploads/' . $filename;

            $file_path = 'uploads/reports/product_stock';
            $file_name = 'user-' . date('Y-m-d-His') . '.xlsx';
        }

        if (ENV('APP_ENV') == 'production') {
            $email = env('MAIL_FROM_ADDRESS');
        } else {
            $email = 'karuppasamy.m@codetez.com';
        }

        Log::info("filename");
        Log::info($filename);

        $report_full_url = CommonComponent::getImageFullUrlPath($file_name, $file_path);

        Log::info("report_full_url");
        Log::info($report_full_url);

        // Mail::to($email)->send(new UserExportMail($report_full_url));

        Log::info("end job");
    }
}
