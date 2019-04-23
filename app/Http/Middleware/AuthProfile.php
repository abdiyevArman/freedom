<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthProfile
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
        if (Auth::check() && Auth::user()->sms_activate != 1) {
            return redirect('/auth/confirm');
        }
        elseif(Auth::check()){
            return redirect('/profile');
        }
        return $next($request);
    }
}
