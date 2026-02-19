<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MachineSaleStore;
use Log;

class MachineSalesBillStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'machine_sale_bill:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Machine Sales Bill Store From Easse Machine';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Schedule run');
        MachineSaleStore::dispatch();
    }
}
