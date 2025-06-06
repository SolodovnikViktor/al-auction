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

class AdminPostFilterController extends Controller
{
    public function adminSearch(Request $request)
    {
        $paginate = 15;
        if (auth()->user()->catalog_view) {
            $paginate = 50;
        }
        return Inertia::render(
            'Admin/Posts/Index',
            [
                'search' => $request->search,
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
//                        ->withInput()
                )
            ]
        );
    }

    public function adminFilterCount(Request $request)
    {
        $this->getPosts($request, $posts);
        return $posts->total();
    }

    public function adminPostsFilter(Request $request)
    {
        $this->getPosts($request, $posts);

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts,

            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),
        ]);
    }

    /**
     * @param Request $request
     * @param $posts
     * @return void
     */
    public function getPosts(Request $request, &$posts): void
    {
        $paginate = 15;
        if (auth()->user()->catalog_view) {
            $paginate = 10;
        }
        $posts = AdminPostIndexResource::collection(
            Post::
            when($request->input('brand_id'), function ($query, $x) {
                $query->where('brand_id', $x);
            })->
            when($request->model_id, function ($query, $x) {
                $query->where('model_id', $x);
            })->
            when($request->body_type_id, function ($query, $x) {
                $query->where('body_type_id', $x);
            })->
            when($request->transmission_id, function ($query, $x) {
                $query->where('transmission_id', $x);
            })->
            when($request->fuel_id, function ($query, $x) {
                $query->where('fuel_id', $x);
            })->
            when($request->wheel_id, function ($query, $x) {
                $query->where('wheel_id', $x);
            })->
            when($request->drive_id, function ($query, $x) {
                $query->where('drive_id', $x);
            })->
            when($request->color_id, function ($query, $x) {
                $query->where('color_id', $x);
            })->
            when($request->price_ot, function ($query, $x) {
                $query->where('price', '>=', $x);
            })->
            when($request->price_do, function ($query, $x) {
                $query->where('price', '<=', $x);
            })->
            when($request->year_ot, function ($query, $x) {
                $query->where('year_release', '>=', $x);
            })->
            when($request->year_do, function ($query, $x) {
                $query->where('year_release', '<=', $x);
            })->
            when($request->mileage_ot, function ($query, $x) {
                $query->where('mileage', '>=', $x);
            })->
            when($request->mileage_do, function ($query, $x) {
                $query->where('mileage', '<=', $x);
            })->paginate($paginate)->withQueryString()
        );
    }
}
