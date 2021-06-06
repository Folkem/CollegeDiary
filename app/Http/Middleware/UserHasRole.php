<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class UserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param Role $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roleNames)
    {
        if (!auth()->check() || !in_array(auth()->user()->role->name, $roleNames)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
