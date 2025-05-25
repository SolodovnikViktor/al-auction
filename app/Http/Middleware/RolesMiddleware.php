<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesMiddleware
{
    public function handle(Request $request, Closure $next, string $role1 = null, $role2 = null): Response
    {
        $userRole = auth()->user()->role_id;

        if ($userRole == $role1 || $userRole == $role2) {
            return $next($request);
        }
        return redirect()->route('home');
    }
}
