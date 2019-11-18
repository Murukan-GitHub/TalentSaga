<?php

namespace Suitcore\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOnlyMiddleware
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
        $user = Auth::guard($guard)->user();

        if ($user->role != 'admin') {
            return redirect(route('frontend.home'));
        }

        return $next($request);
    }
}
