<?php

namespace Tests\Feature\API;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProtectedApiAuthenticationCoverageTest extends TestCase
{
    public function test_all_admin_protected_api_routes_require_authentication(): void
    {
        $failures = $this->assertProtectedRoutesRequireAuth('api/v1/', 'Authenticate:api');
        $this->assertEmpty($failures, "Admin API auth guard failures:\n".implode("\n", $failures));
    }

    public function test_all_supplier_protected_api_routes_require_authentication(): void
    {
        $failures = $this->assertProtectedRoutesRequireAuth('api/supplier/v1/', 'Authenticate:supplier');
        $this->assertEmpty($failures, "Supplier API auth guard failures:\n".implode("\n", $failures));
    }

    /**
     * @return array<int, string>
     */
    private function assertProtectedRoutesRequireAuth(string $prefix, string $authMiddlewareNeedle): array
    {
        $failures = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (strpos($uri, $prefix) !== 0) {
                continue;
            }

            $middleware = implode('|', $route->gatherMiddleware());
            if (strpos($middleware, $authMiddlewareNeedle) === false) {
                continue;
            }

            $methods = array_values(array_diff($route->methods(), ['HEAD', 'OPTIONS']));
            if (empty($methods)) {
                continue;
            }

            $response = $this->json($methods[0], '/'.$this->resolveUriPlaceholders($uri), []);
            if ($response->getStatusCode() !== 401) {
                $failures[] = sprintf('%s %s returned %d', $methods[0], $uri, $response->getStatusCode());
            }
        }

        return $failures;
    }

    private function resolveUriPlaceholders(string $uri): string
    {
        return preg_replace('/\{[^}]+\}/', '1', $uri) ?? $uri;
    }
}
