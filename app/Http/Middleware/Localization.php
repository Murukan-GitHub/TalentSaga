<?php

namespace App\Http\Middleware;

use Route;
use Closure;
use Cookie;

class Localization
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
        // set languages if requested, based on request and saved session
        $defaultLocale = strtolower(config('app.fallback_locale', 'en'));
        $localeOptions = explode(',', env('APP_MULTI_LOCALE_OPTIONS', $defaultLocale));
        // priority 2
        if (!$request->session()->has('custom_locale') &&
            auth()->check() && 
            auth()->user()->language_setting) {
            $locale = auth()->user()->language_setting;
            if (in_array($locale, $localeOptions)) {
                $request->session()->put('locale', $locale);
                $request->session()->put('custom_locale', $locale);
                $request->session()->save();
            }
        }
        // priority 1
        if (request()->has('locale')) {
            $locale = request()->get('locale');
            if (in_array($locale, $localeOptions)) {
                $request->session()->put('locale', $locale);
                $request->session()->put('custom_locale', $locale);
                $request->session()->save();
            }
        }
        // set
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
            if (in_array($locale, $localeOptions)) {
                app()->setLocale($locale); 
                Cookie::queue('custom_locale', $locale);
            }
        }
        // send request to next process/middleware
        return $next($request);
    }
}
