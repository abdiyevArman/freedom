<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Profile
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
        if (Auth::check() && Auth::user()->is_confirm_phone != 1) {
            return redirect('/auth/confirm');
        }
        elseif (!Auth::check()) {
            return redirect('/auth/login');
        }

        return $next($request);
    }
}
