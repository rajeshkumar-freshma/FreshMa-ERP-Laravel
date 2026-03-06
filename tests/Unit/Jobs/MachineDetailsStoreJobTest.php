<?php

namespace Tests\Unit\Jobs;

use App\Jobs\MachineDetailsStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MachineDetailsStoreJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_handles_without_external_sqlsrv_call()
    {
        $job = new MachineDetailsStore();
        $job->handle();

        $this->assertDatabaseCount('p_l_u_masters', 0);
        $this->assertDatabaseCount('machine_data', 0);
    }
}
