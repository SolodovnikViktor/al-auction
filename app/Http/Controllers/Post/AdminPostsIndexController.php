<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Admin\Post\AdminPostIndexResource;
use App\Http\Resources\Admin\Post\AdminPostShowResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Photo;
use App\Models\PhotoPosition;
use App\Models\Post;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class AdminPostsIndexController extends Controller
{
    public function index(Request $request): Response
    {
        $paginate = 15;
        if (auth()->user()->catalog_view) {
            $paginate = 20;
        }

        if ($request->ordering_value) {
            $posts = Post::orderBy($request->ordering_value, $request->ordering_direction)->paginate(
                $paginate
            )->withQueryString();
        } else {
            $posts = Post::latest()->paginate($paginate);
        }


        return Inertia::render('Admin/Posts/Index', [
            'posts' => AdminPostIndexResource::collection($posts),
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),

            'orderingValue' => $request->ordering_value,
            'orderingDirection' => $request->ordering_direction,
            'orderingDesc' => $request->ordering_desc,
            'orderingAsc' => $request->ordering_asc,
            'headerIndex' => $request->header_index,
        ]);
    }

}
