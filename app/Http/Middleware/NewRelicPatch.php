<?php

namespace App\Http\Middleware;

use Closure;

class NewRelicPatch
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
        $response = $next($request);
        event('router.filter:after:newrelic-patch', [$request, $response], true);
        return $response;
    }
}
