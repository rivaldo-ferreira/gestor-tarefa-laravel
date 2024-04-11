<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    //  MIDDLEWARE PARA LOGOUT
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('username')) {
            return redirect()->route('index');
        }
        return $next($request);
    }
}
