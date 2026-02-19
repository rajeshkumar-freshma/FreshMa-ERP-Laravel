<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MachineSaletoSaleOrderJob;
use Log;

class MachineSaletoSaleOrderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sale_order:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Machine Sales Converted to Live Bill Store From Easse Machine';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Live Conversion Schedule run');
        MachineSaletoSaleOrderJob::dispatch();
    }
}
