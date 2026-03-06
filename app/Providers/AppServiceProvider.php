<?php

namespace App\Providers;

use App\Core\KTBootstrap;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Update defaultStringLength
        Builder::defaultStringLength(191);

        KTBootstrap::init();

        if (filter_var(env('DB_SLOW_QUERY_LOG', false), FILTER_VALIDATE_BOOLEAN)) {
            $slowMs = (int) env('DB_SLOW_QUERY_MS', 200);
            DB::listen(function ($query) use ($slowMs) {
                if ($query->time < $slowMs) {
                    return;
                }

                Log::warning('Slow query detected', [
                    'time_ms' => $query->time,
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                ]);
            });
        }

        // Catch N+1 and unsafe model writes during development/testing.
        Model::preventLazyLoading(!app()->environment('production'));
        Model::preventSilentlyDiscardingAttributes(!app()->environment('production'));

        // Builder::macro('baseModel', function () {
        //     return new BaseModel;
        // });
    }
}
