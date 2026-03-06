<?php

namespace Tests\Unit\Console;

use App\Console\Commands\ProductPriceUpdateCron;
use App\Jobs\ProductPriceUpdateJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductPriceUpdateCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_job()
    {
        Queue::fake();

        (new ProductPriceUpdateCron())->handle();

        Queue::assertPushed(ProductPriceUpdateJob::class);
    }
}
