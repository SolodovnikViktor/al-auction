<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Post\AdminPostIndexResource;
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
use Inertia\Response;

class AdminPostIndexController extends Controller
{
    public function index(Request $request): Response
    {
        $this->getPosts($request, $posts, $user);

        return Inertia::render('Admin/Post/Index', [
            'posts' => AdminPostIndexResource::collection($posts),
            'user' => auth()->user(),
            'formSearch' => $request->formSearch,
            'formFilter' => $request->formFilter,
            'formOrdering' => $request->formOrdering,
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),
        ]);
    }

    public function search(Request $request)
    {
        $paginate = 15;
        if (auth()->user()->catalog_view) {
            $paginate = 25;
        }
        return Inertia::render(
            'Admin/Post/Index',
            [
                'search' => $request->search,
                'user' => auth()->user(),
                'brands' => Brand::all(),
                'fuels' => Fuel::all(),
                'wheels' => Wheel::all(),
                'colors' => Color::all(),
                'drives' => Drive::all(),
                'bodyTypes' => BodyType::all(),
                'transmissions' => Transmission::all(),
                'posts' => AdminPostIndexResource::collection(
                    Post::query()
                        ->when($request->search, function ($query, $search) {
                            $query->where('vin', 'like', '%' . $search . '%')
                                ->OrWhere('id', 'like', '%' . $search . '%');
                        })->paginate($paginate)
                        ->withQueryString()
                )
            ]
        );
    }
}
