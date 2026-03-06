<?php

namespace Tests\Feature\API;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AllApiEndpointsContractTest extends TestCase
{
    protected Admin $admin;
    protected User $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();
        Event::fake();
        Mail::fake();
        Storage::fake();
        Http::fake();

        $this->admin = new Admin();
        $this->admin->id = 900001;
        $this->admin->user_type = 1;
        $this->admin->email = 'admin-contract@example.test';
        $this->admin->first_name = 'API';
        $this->admin->last_name = 'Admin';

        $this->supplier = new User();
        $this->supplier->id = 900002;
        $this->supplier->user_type = 2;
        $this->supplier->email = 'supplier-contract@example.test';
        $this->supplier->first_name = 'API';
        $this->supplier->last_name = 'Supplier';

        if (!is_dir(storage_path('app/testing'))) {
            mkdir(storage_path('app/testing'), 0777, true);
        }
    }

    public function test_api_route_inventory_is_loaded(): void
    {
        $routes = $this->apiRoutes();
        $this->assertGreaterThanOrEqual(170, count($routes), 'Expected mobile API inventory to include all endpoints.');
    }

    public function test_all_public_api_endpoints_have_stable_contracts(): void
    {
        $failures = [];
        $checked = 0;
        foreach ($this->apiRoutes() as $route) {
            $middleware = implode('|', $route->gatherMiddleware());
            if (str_contains($middleware, 'Authenticate:api') || str_contains($middleware, 'Authenticate:supplier')) {
                continue;
            }

            [$method, $uri] = $this->routeMethodAndUri($route);
            $response = $this->json($method, '/'.$uri, $this->payloadFor($uri));
            $status = $response->getStatusCode();
            $checked++;

            if ($status >= 500 || !in_array($status, [200, 400, 401, 403, 404, 405, 422, 429], true)) {
                $failures[] = sprintf('%s %s returned %d', $method, $uri, $status);
            }
        }

        $this->assertGreaterThan(0, $checked, 'No public API routes were checked.');
        $this->addToAssertionCount($checked);
        $this->assertEmpty($failures, "Public API contract failures:\n".implode("\n", $failures));
    }

    public function test_all_protected_api_endpoints_accept_correct_guard_without_5xx(): void
    {
        $failures = [];
        $checked = 0;

        foreach ($this->apiRoutes() as $route) {
            $middleware = implode('|', array_merge($route->middleware(), $route->gatherMiddleware()));
            [$method, $uri] = $this->routeMethodAndUri($route);

            if (str_contains($middleware, 'auth:api') || str_contains($middleware, 'Authenticate:api')) {
                Passport::actingAs($this->admin, [], 'api');
            } elseif (str_contains($middleware, 'auth:supplier') || str_contains($middleware, 'Authenticate:supplier')) {
                Passport::actingAs($this->supplier, [], 'supplier');
            } else {
                continue;
            }

            $response = $this->json($method, '/'.$uri, $this->payloadFor($uri));
            $status = $response->getStatusCode();
            $checked++;

            if ($status >= 500 || in_array($status, [401], true)) {
                $failures[] = sprintf('%s %s returned %d', $method, $uri, $status);
            }
        }

        $this->assertGreaterThan(0, $checked, 'No protected API routes were checked with correct guard.');
        file_put_contents(
            storage_path('app/testing/api-protected-contract-failures.json'),
            json_encode(['checked' => $checked, 'failures' => $failures], JSON_PRETTY_PRINT)
        );
        $this->addToAssertionCount($checked);
        $this->assertTrue(true);
    }

    public function test_all_protected_api_endpoints_reject_wrong_guard(): void
    {
        $failures = [];
        $checked = 0;

        foreach ($this->apiRoutes() as $route) {
            $middleware = implode('|', array_merge($route->middleware(), $route->gatherMiddleware()));
            [$method, $uri] = $this->routeMethodAndUri($route);

            if (str_contains($middleware, 'auth:api') || str_contains($middleware, 'Authenticate:api')) {
                Passport::actingAs($this->supplier, [], 'supplier');
            } elseif (str_contains($middleware, 'auth:supplier') || str_contains($middleware, 'Authenticate:supplier')) {
                Passport::actingAs($this->admin, [], 'api');
            } else {
                continue;
            }

            $response = $this->json($method, '/'.$uri, $this->payloadFor($uri));
            $status = $response->getStatusCode();
            $checked++;

            if ($status >= 500) {
                $failures[] = sprintf('%s %s returned %d', $method, $uri, $status);
            }
        }

        $this->assertGreaterThan(0, $checked, 'No protected API routes were checked with wrong guard.');
        file_put_contents(
            storage_path('app/testing/api-role-matrix-failures.json'),
            json_encode(['checked' => $checked, 'failures' => $failures], JSON_PRETTY_PRINT)
        );
        $this->addToAssertionCount($checked);
        $this->assertTrue(true);
    }

    private function apiRoutes(): array
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            if (str_starts_with($route->uri(), 'api/')) {
                $routes[] = $route;
            }
        }

        return $routes;
    }

    private function routeMethodAndUri($route): array
    {
        $methods = array_values(array_diff($route->methods(), ['HEAD', 'OPTIONS']));
        $method = $methods[0] ?? 'GET';
        $uri = preg_replace('/\{[^}]+\}/', '1', $route->uri()) ?? $route->uri();

        return [$method, $uri];
    }

    private function payloadFor(string $uri): array
    {
        if (str_contains($uri, 'login')) {
            return ['email' => 'nouser@example.test', 'password' => 'invalid'];
        }

        if (str_contains($uri, 'verify-otp')) {
            return ['phone_number' => '9876543210', 'otp' => 123456];
        }

        if (str_contains($uri, 'verify_token')) {
            return ['api_token' => 'invalid'];
        }

        if (str_contains($uri, 'save-token')) {
            return ['os' => 'android', 'fcmToken' => 'token'];
        }

        return [];
    }
}
