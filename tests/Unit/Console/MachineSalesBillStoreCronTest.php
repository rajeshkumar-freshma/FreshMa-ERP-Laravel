<?php

namespace Tests\Unit\Console;

use App\Console\Commands\MachineSalesBillStore;
use App\Jobs\MachineSaleStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MachineSalesBillStoreCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_job()
    {
        Queue::fake();

        (new MachineSalesBillStore())->handle();

        Queue::assertPushed(MachineSaleStore::class);
    }
}
