<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProductStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStoreJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_handles_without_external_sqlsrv_call()
    {
        // SQLSRV_EASE_MOCK is true in .env.testing, so the job should no-op safely.
        $job = new ProductStore();
        $job->handle();

        // No exception means pass; database remains empty for products.
        $this->assertDatabaseCount('products', 0);
    }
}
