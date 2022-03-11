<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*');
        ##tanda * untuk semua domain berbeda dan dapat diganti ke spesifik domain misal https://zonacoding.com
    }
}
