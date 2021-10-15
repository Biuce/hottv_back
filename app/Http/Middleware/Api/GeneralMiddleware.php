<?php

namespace App\Http\Middleware\Api;

use Closure;
use App\Api\General\Auth;

class GeneralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        (new Auth())->checkRequestAuth($request->header());
        return $next($request);
    }
}
