<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            if (session()->has('redirectTo')) {
                return redirect()->to(session()->pull('redirectTo'));
            }
            
            if (Auth::guard($guard)->user()->role == "admin") {
                return redirect()->intended(route('backend.home.index'));
            }

            if (Auth::guard($guard)->user()->isActive()) {
                return redirect()->intended(route('frontend.home'));
            }

            return redirect()->intended(route('frontend.home'));
        }

        return $next($request);
    }
}
