<?php

namespace Suitcore\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserOnlyApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status'  => 40,
                'message' => 'API access failed! This service only for authenticated user!',
            ]);
        }

        return $next($request);
    }
}
