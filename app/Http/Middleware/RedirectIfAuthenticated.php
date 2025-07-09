<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user->role === 'Admin') {
                    return redirect('/admin/dashboard');
                } elseif ($user->role === 'Peternak') {
                    return redirect()->route('peternak.dashboard');
                } elseif ($user->role === 'Penyuluh') {
                    return redirect()->route('penyuluh.dashboard');
                } else {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
