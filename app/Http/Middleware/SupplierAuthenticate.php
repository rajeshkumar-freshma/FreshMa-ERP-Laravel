<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class SupplierAuthenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Dashboard route accessed', ['user' => Auth::guard('supplier')->user()]);
        if (!Auth::guard('supplier')->check()) {
            return response()->json(['message' => 'Authentication Error.'], 401);
        }
        return $next($request);
    }

}
