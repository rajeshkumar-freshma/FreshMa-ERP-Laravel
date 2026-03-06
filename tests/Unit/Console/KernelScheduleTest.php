<?php

namespace Tests\Unit\Console;

use App\Console\Kernel as AppConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class KernelScheduleTest extends TestCase
{
    public function test_three_minute_crons_are_registered_with_expected_frequency(): void
    {
        $schedule = new Schedule($this->app);
        $kernel = $this->app->make(AppConsoleKernel::class);

        $method = new \ReflectionMethod($kernel, 'schedule');
        $method->setAccessible(true);
        $method->invoke($kernel, $schedule);

        $events = collect($schedule->events());

        $expectedThreeMinuteCommands = [
            'product:store',
            'machine_details:store',
            'productprice:update',
            'machine_sale_bill:store',
            'sale_order:store',
            'store_stock:update',
        ];

        foreach ($expectedThreeMinuteCommands as $command) {
            $event = $events->first(function ($item) use ($command) {
                return str_contains($item->command, $command);
            });

            $this->assertNotNull($event, "Expected scheduled command not found: {$command}");
            $this->assertSame('*/3 * * * *', $event->expression);
        }
    }

    public function test_queue_worker_is_scheduled_every_minute(): void
    {
        $schedule = new Schedule($this->app);
        $kernel = $this->app->make(AppConsoleKernel::class);

        $method = new \ReflectionMethod($kernel, 'schedule');
        $method->setAccessible(true);
        $method->invoke($kernel, $schedule);

        $event = collect($schedule->events())->first(function ($item) {
            return str_contains($item->command, 'queue:work --stop-when-empty');
        });

        $this->assertNotNull($event);
        $this->assertSame('* * * * *', $event->expression);
    }
}
