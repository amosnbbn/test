<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('user')) {
            return redirect('/')->with('error', 'Silahkan login dulu!');
        }
        return $next($request);
    }
}