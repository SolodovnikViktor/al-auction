<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesMiddleware
{
    public function handle(Request $request, Closure $next, string $role1 = null, $role2 = null): Response
    {
        $userRole = auth()->user()->Role->value;

        if ($userRole == $role1 || $userRole == $role2) {
            return $next($request);
        }
        if (auth()->user()) {
            return back()->with('message_bet', 'Чтобы сделать ставки, свяжитесь с менеджером.');
        } else {
            return redirect()->route('home');
        }
    }
}
