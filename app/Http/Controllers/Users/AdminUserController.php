<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\AdminUserIndexResource;
use App\Http\Resources\Admin\User\AdminUserShowResource;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        return Inertia::render('Admin/Users/Index', [
            'users' => AdminUserIndexResource::collection($users),
            'roles' => Role::all(),
            'orderingValue' => $request->ordering_value,
            'orderingDirection' => $request->ordering_direction,
            'orderingDesc' => $request->ordering_desc,
            'orderingAsc' => $request->ordering_asc,
            'headerIndex' => $request->header_index,
            'search' => $request->search,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate(['role_id' => ['required']]);
        $user->update($validated);
    }

    public function show(User $user): Response
    {
        return Inertia::render('Admin/Users/Show', [
            'user' => new AdminUserShowResource($user),
        ]);
    }

    public function destroy(Post $post)
    {
        $images = Photo::where('post_id', $post->id)->get();
        foreach ($images as $image) {
            Storage::deleteDirectory($image->folder);
            $image->delete();
        }
        $post->delete();
        return to_route('admin-posts.index')->with('message', 'Объявление удалено');
    }
}
