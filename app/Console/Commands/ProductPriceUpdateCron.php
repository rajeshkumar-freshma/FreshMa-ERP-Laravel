<?php

namespace App\Console\Commands;

use App\Jobs\ProductPriceUpdateJob;
use Illuminate\Console\Command;
use Log;

class ProductPriceUpdateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productprice:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Price Update From Easse Machine';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Product Price Update Schedule run');
        ProductPriceUpdateJob::dispatch();
    }
}
