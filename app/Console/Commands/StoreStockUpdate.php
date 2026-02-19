<?php

namespace App\Console\Commands;

use App\Jobs\StockStockUpdateJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StoreStockUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store_stock:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Update for Store';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Stock Update Schedule run');
        StockStockUpdateJob::dispatch();
    }
}
