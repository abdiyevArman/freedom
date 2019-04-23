<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class CheckSmsActivate
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
        if (Auth::check() && Auth::user()->is_confirm_phone == 1) {
            return redirect('/profile');
        }
        elseif(!Auth::check()){
            return redirect('/auth/login');
        }

        return $next($request);
    }
}
