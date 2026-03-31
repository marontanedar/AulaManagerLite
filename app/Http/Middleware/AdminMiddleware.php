<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Acceso restringido a administradores.');
        }

        return $next($request);
    }

    // public function handle(Request $request, Closure $next)
    // {
    //     dd(Auth::user(), Auth::user()->role, Auth::user()->isAdmin());
    // }
}
