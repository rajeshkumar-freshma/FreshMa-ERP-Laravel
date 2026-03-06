<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:runnow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Process current queue and exit instead of starting a long-running listener.
        \Artisan::call('queue:work --stop-when-empty');
    }
}
