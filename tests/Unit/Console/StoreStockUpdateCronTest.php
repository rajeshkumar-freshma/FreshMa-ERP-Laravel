<?php

namespace Tests\Unit\Console;

use App\Console\Commands\StoreStockUpdate;
use App\Jobs\StockStockUpdateJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StoreStockUpdateCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_job()
    {
        Queue::fake();

        (new StoreStockUpdate())->handle();

        Queue::assertPushed(StockStockUpdateJob::class);
    }
}
