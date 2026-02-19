<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MachineDetailsStore;
use Illuminate\Support\Facades\Log;

class MachineDetailsStoreCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'machine_details:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Machine Details Store From Easse Machine';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Machine Data Schedule run');
        MachineDetailsStore::dispatch();
    }
}
