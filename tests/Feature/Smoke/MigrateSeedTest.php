<?php

namespace Tests\Feature\Smoke;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MigrateSeedTest extends TestCase
{
    /**
     * Ensure migrations and seeders run cleanly on a fresh database.
     */
    public function test_migrate_fresh_seed_completes_successfully()
    {
        $exit = Artisan::call('migrate:fresh', ['--seed' => true]);
        $this->assertSame(0, $exit, 'migrate:fresh --seed failed');
    }
}
