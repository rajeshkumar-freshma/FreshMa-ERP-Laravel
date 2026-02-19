<?php

namespace App\Console\Commands;

use App\Jobs\ProductStore;
use Illuminate\Console\Command;
use Log;

class ProductStoreCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Details Store From Easse Machine';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Product Schedule run');
        ProductStore::dispatch();
    }
}
