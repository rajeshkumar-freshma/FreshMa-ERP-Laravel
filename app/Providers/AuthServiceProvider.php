<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Auth\SupplierUserProvider; // Correct import for your custom provider
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        // $this->registerPolicies();

        // Auth::provider('supplier', function ($app, array $config) {
        //     return new SupplierUserProvider($app['hash'], $config['model']);
        // });
        // Passport::routes();
        // Gate::define("isAdmin", function ($user) {
        //     return $user->hasRole("Super Admin");
        // });

        // Gate::define("isAdmin", function ($user) {
        //     return $user->hasRole("Admin");
        // });

        // Gate::define("isAdmin", function ($user) {
        //     return $user->hasRole("Manager");
        // });
        // Gate::define("isAdmin", function ($user) {
        //     return $user->hasRole("Store Manager");
        // });
    }
}
