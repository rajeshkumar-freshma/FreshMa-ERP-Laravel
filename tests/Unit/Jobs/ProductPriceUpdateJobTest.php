<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProductPriceUpdateJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPriceUpdateJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_handles_without_external_sqlsrv_call_and_no_products()
    {
        $job = new ProductPriceUpdateJob();
        $job->handle();

        // No products means no price rows should exist after job.
        $this->assertDatabaseCount('product_prices', 0);
        $this->assertDatabaseCount('product_price_histories', 0);
    }
}
