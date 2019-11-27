<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class Internationalization
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
        if (auth()->check()) {
            $timezone   = auth()->user()->timezone;
            $locale     = auth()->user()->locale;
        } else {
            $timezone   = config('app.timezone');
            $locale     = config('app.locale');
        }

        config(['app.timezone' => $timezone, 'app.locale' => $locale]);

        $hours = hoursTimezone($timezone);

        DB::update('SET time_zone = ?', [$hours]);
        //dd(config('app.timezone'));

        return $next($request);
    }
}
