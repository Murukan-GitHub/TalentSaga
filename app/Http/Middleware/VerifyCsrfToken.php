<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    	//
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            $message = 'Maaf, session Anda sudah berakhir. Silakan coba kembali.';
            $webNotification = $request->session()->get('webNotification', []);
            $webNotification[] = ['type' => 'error', 'title' => 'Terjadi Kesalahan', 'message' => $message];
            $request->session()->flash('webNotification', $webNotification);
            $request->session()->flash('message', $message);
            return back();
        }
    }
}
