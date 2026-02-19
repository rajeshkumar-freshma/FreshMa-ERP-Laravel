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
        if (ENV('CRON_ENVIRONMENT') == 'production') {
            $schedule->command('product:store')->daily();
            $schedule->command('machine_details:store')->daily();
            $schedule->command('productprice:update')->daily();
            $schedule->command('machine_sale_bill:store')->everyFiveMinutes();
            $schedule->command('sale_order:store')->everyFiveMinutes();
            $schedule->command('store_stock:update')->daily();
            // $schedule->command('import:excel')->daily();

            // $schedule->command('push_notification:send')->daily();
            // $schedule->command('purchase_invoice:send')->everyMinute();
            // $schedule->command('download:s3files')->everyMinute();     // run every hour at 3 minute
            // $schedule->command('salary:pay-silp-auto-generate-cron')->monthlyOn(1, '00:00');
            // $schedule->command('activitylog:clean')->daily();
        } else {
            $schedule->command('product:store')->everyMinute();
            $schedule->command('machine_details:store')->everyMinute();
            $schedule->command('productprice:update')->everyMinute();
            $schedule->command('machine_sale_bill:store')->everyMinute();
            $schedule->command('sale_order:store')->everyMinute();
            $schedule->command('store_stock:update')->everyMinute();
            // $schedule->command('import:excel')->daily();

            // $schedule->command('push_notification:send')->daily();
            // $schedule->command('purchase_invoice:send')->everyMinute();
            // $schedule->command('download:s3files')->everyMinute();     // run every hour at 3 minute
            // $schedule->command('salary:pay-silp-auto-generate-cron')->everyMinute();
            // $schedule->command('activitylog:clean')->daily();
        }

        \Artisan::call('queue:work --stop-when-empty');
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
