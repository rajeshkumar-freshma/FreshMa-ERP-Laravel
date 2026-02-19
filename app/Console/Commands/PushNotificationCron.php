<?php

namespace App\Console\Commands;

use App\Jobs\PushNotification;
use Illuminate\Console\Command;
use Log;

class PushNotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push_notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification send From Users ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Push Notification Send  Schedule run');
        PushNotification::dispatch();
    }
}
