<?php

namespace App\Http\Middleware;

use Closure;
use function abort;

class CheckIsAdmin
{
    public function handle($request, Closure $next) {
        abort_if(hasAccess($request->route()->getName()) == false, 403, 'Access Denied');
        return $next($request);
    }
}
