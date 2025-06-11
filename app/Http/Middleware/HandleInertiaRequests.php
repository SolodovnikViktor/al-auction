<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'role' => fn() => $request->user()
                    ? $request->user()->only('role')
                    : null,
            ],
            'csrf_token' => csrf_token(),
            'message_form' => $request->session()->get('message_form'),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'message' => session('message')
            ]
        ];
    }
}

//'auth.user' => fn() => $request->user()
//    ? $request->user()->only('id', 'name', 'email', 'Role')
//    : null,

//function () {
//    if (auth()->user()) {
//        return new MainUserResource(auth()->user());
//    }
//},
