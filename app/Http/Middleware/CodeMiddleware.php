<?php

namespace App\Http\Middleware;

use Closure;

class CodeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->getClientIp(), ['127.0.0.1'])) {
            return $next($request);
        }
    }
}
