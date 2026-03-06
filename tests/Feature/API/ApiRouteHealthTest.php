<?php

namespace Tests\Feature\API;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ApiRouteHealthTest extends TestCase
{
    public function test_all_api_routes_return_non_5xx_without_authentication(): void
    {
        $allowedStatuses = [200, 201, 202, 204, 400, 401, 403, 404, 405, 422, 429];
        $failures = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (strpos($uri, 'api/') !== 0) {
                continue;
            }

            $methods = array_values(array_diff($route->methods(), ['HEAD', 'OPTIONS']));
            if (empty($methods)) {
                continue;
            }

            $method = $methods[0];
            $response = $this->json($method, '/'.$uri, []);
            $status = $response->getStatusCode();

            if ($status >= 500 || !in_array($status, $allowedStatuses, true)) {
                $failures[] = sprintf('%s %s returned %d', $method, $uri, $status);
            }
        }

        $this->assertEmpty($failures, "Unexpected API responses:\n".implode("\n", $failures));
    }
}
