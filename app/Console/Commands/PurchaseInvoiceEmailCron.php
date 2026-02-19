<?php

namespace App\Console\Commands;

use App\Jobs\PurchaseOrderInvoiceJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurchaseInvoiceEmailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase_invoice:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice Email Send';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Purchase Invoice Email Schedule run');
        PurchaseOrderInvoiceJob::dispatch();
    }
}
