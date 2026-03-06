<?php

namespace Tests\Unit\Console;

use App\Console\Commands\ProductStoreCron;
use App\Jobs\ProductStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductStoreCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_job()
    {
        Queue::fake();

        (new ProductStoreCron())->handle();

        Queue::assertPushed(ProductStore::class);
    }
}
