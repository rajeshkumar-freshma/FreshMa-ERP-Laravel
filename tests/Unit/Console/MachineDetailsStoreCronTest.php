<?php

namespace Tests\Unit\Console;

use App\Console\Commands\MachineDetailsStoreCron;
use App\Jobs\MachineDetailsStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MachineDetailsStoreCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_job()
    {
        Queue::fake();

        (new MachineDetailsStoreCron())->handle();

        Queue::assertPushed(MachineDetailsStore::class);
    }
}
