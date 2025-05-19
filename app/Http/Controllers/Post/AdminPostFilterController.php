<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PostIndexResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Post;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPostFilterController extends Controller
{
    public function adminSearch(Request $request)
    {
//        $validated = $request->validate(['search' => ['required']]);
//        $search = ($validated['search']);

//        $search = $validated->get('search');
//        $posts = Post::where('vin', 'LIKE', '%' . $search . '%')->get();
//        $postsPaginate = Post::with('user', 'images', 'imagesPath')->paginate(15);

//        $posts = PostIndexResource::collection(
//            Post::where('vin', 'LIKE', '%' . $search . '%')->paginate(15)
//        );
//        return Inertia::render('Admin/Index', [
//            'posts' => $posts,
//        ]);

        return Inertia::render(
            'Admin/Index',
            [
                'brands' => Brand::all(),
                'fuels' => Fuel::all(),
                'wheels' => Wheel::all(),
                'colors' => Color::all(),
                'drives' => Drive::all(),
                'bodyTypes' => BodyType::all(),
                'transmissions' => Transmission::all(),
                'posts' => PostIndexResource::collection(
                    Post::query()
                        ->when($request->input('search'), function ($query, $search) {
                            $query->where('vin', 'like', '%' . $search . '%')
                                ->OrWhere('id', 'like', '%' . $search . '%');
                        })->paginate(8)
                        ->withQueryString()
                )
            ]
        );
    }

    public function adminFilter(Request $request)
    {
        $search = ($request['brand_id']);
        dd($search);

//        $search = $validated->get('search');
//        dd($search);
//        $posts = Post::where('vin', 'LIKE', '%' . $search . '%')->get();

//        $postsPaginate = Post::with('user', 'images', 'imagesPath')->paginate(15);
        $posts = PostIndexResource::collection(Post::where('vin', 'LIKE', '%' . $search . '%')->paginate(15));
        return Inertia::render('Admin/Index', [
            'posts' => $posts,
        ]);
    }

}
