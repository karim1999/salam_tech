<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    public function handle($request, Closure $next)
    {
        if (request()->header('lang')) $lang = request()->header('lang');
        else $lang = config('app.fallback_locale');
        app()->setLocale($lang);

        return $next($request);
    }
}
