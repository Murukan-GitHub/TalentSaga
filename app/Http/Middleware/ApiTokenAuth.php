<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Auth;

class ApiTokenAuth
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
        $token     = $request->get('token');
        $userToken = UserToken::where('token', '=', $token)->with('user')->first();

        if ($userToken && $userToken->user && $userToken->user->status != User::STATUS_BANNED) {
            Auth::onceUsingId($userToken->user_id);
        }

        return $next($request);
    }
}
