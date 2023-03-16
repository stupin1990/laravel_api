<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SqlLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return App\Http\Middleware\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!env('APP_DEBUG')) {
            return $next($request);
        }

        DB::enableQueryLog();

        $next($request);

        $log = DB::getQueryLog();
        dd($log);
    }
}
