<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AdminUserIndexResource;
use App\Http\Resources\User\AdminUserShowResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(Request $request): Response
    {
        $orderingValue = 'created_at';
        if ($request->ordering_value) {
            $orderingValue = $request->ordering_value;
        }
        $orderingDirection = 'desc';
        if ($request->ordering_direction) {
            $orderingDirection = $request->ordering_direction;
        }
        if ($request->search) {
            $users = User::query()
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->OrWhere('id', 'like', '%' . $search . '%')
                        ->OrWhere('surname', 'like', '%' . $search . '%')
                        ->OrWhere('phone', 'like', '%' . $search . '%')
                        ->OrWhere('email', 'like', '%' . $search . '%');
                })->orderBy($orderingValue, $orderingDirection)->paginate(
                    20
                )->withQueryString();
        } elseif ($request->ordering_value) {
            $users = User::orderBy($orderingValue, $orderingDirection)->paginate(
                20
            )->withQueryString();
        } else {
            $users = User::latest()->paginate(20);
        }
        return Inertia::render('Admin/User/Index', [
            'users' => AdminUserIndexResource::collection($users),
            'roles' => Role::all(),
            'formOrdering' => $request->query(),
            'usersCount' => User::count(),
            'usersTrusted' => User::where('role_id', 2)->count(),
            'usersOnline' => DB::table(config('session.table'))->count(),
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate(['role_id' => ['required']]);
        $user->update($validated);
    }

    public function show(User $user): Response
    {
        return Inertia::render('Admin/User/Show', [
            'user' => new AdminUserShowResource($user),
        ]);
    }
}
