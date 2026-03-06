<?php

namespace Tests\Unit\Console;

use App\Console\Commands\MachineSaletoSaleOrderCron;
use App\Jobs\MachineSaletoSaleOrderJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MachineSaletoSaleOrderCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_job()
    {
        Queue::fake();

        (new MachineSaletoSaleOrderCron())->handle();

        Queue::assertPushed(MachineSaletoSaleOrderJob::class);
    }
}
