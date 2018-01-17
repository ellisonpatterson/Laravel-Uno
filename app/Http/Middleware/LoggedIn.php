<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoggedIn
{
    /**
     * @param         $request
     * @param Closure $next
     * @param         $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect()->route('auth.login')->withFlashAlert('You must be logged in to continue!');
        }

        return $next($request);
    }
}
