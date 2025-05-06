<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPostFilterController extends Controller
{
    public function adminSearch(Request $request)
    {
        $validated = $request->validate(['search' => ['required']]);
        $search = ($validated['search']);
//        return to_route('admin-post.index');
//        $search = $validated->get('search');
//        dd($search);
//        $posts = Post::where('vin', 'LIKE', '%' . $search . '%')->get();

//        $postsPaginate = Post::with('user', 'images', 'imagesPath')->paginate(15);
        $posts = PostResource::collection(Post::where('vin', 'LIKE', '%' . $search . '%')->paginate(15));
        return Inertia::render('Admin/Index', [
            'posts' => $posts,
        ]);
    }

}
