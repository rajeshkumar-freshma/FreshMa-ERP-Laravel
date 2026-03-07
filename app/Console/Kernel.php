<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\MachineSalesBillStore::class,
        Commands\ProductStoreCron::class,
        Commands\PurchaseInvoiceEmailCron::class,
        \App\Console\Commands\DropSecondDatabaseTables::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // During unit tests we want schedules to be registered so tests can assert on them.
        if (! $this->app->runningUnitTests() && ! filter_var(env('CRON_SYNC_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        if ($this->app->runningUnitTests() || filter_var(env('CRON_FULL_SYNC_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            $fullSyncCron = env('CRON_FULL_SYNC_CRON', '*/3 * * * *');
            $schedule->command('product:store')->cron($fullSyncCron);
            $schedule->command('machine_details:store')->cron($fullSyncCron);
            $schedule->command('productprice:update')->cron($fullSyncCron);
            $schedule->command('store_stock:update')->cron($fullSyncCron);
        }

        if ($this->app->runningUnitTests() || filter_var(env('CRON_SALES_SYNC_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            $salesSyncCron = env('CRON_SALES_SYNC_CRON', '*/3 * * * *');
            $schedule->command('machine_sale_bill:store')->cron($salesSyncCron);
            $schedule->command('sale_order:store')->cron($salesSyncCron);
        }

        // if ($this->app->runningUnitTests() || filter_var(env('CRON_QUEUE_WORKER_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
        //     $queueWorkerCron = env('CRON_QUEUE_WORKER_CRON', '* * * * *');
        //     $schedule->command('queue:work --stop-when-empty')->cron($queueWorkerCron)->withoutOverlapping();
        // }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
