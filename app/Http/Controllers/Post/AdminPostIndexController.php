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

class AdminPostIndexController extends Controller
{
    public function index(Request $request): Response
    {
        $this->getPosts($request, $posts);

        return Inertia::render('Admin/Posts/Index', [
            'posts' => AdminPostIndexResource::collection($posts),
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
            $paginate = 5;
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
                )
            ]
        );
    }

    public function adminFilterCount(Request $request)
    {
        $this->getPosts($request, $posts);
        return $posts->total();
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

        $posts = new Post();

        if ($request->formFilter) {
            $formFilter = $request->formFilter;
            if (isset($formFilter['brand_id'])) {
                $posts = $posts->
                when($formFilter['brand_id'], function ($query, $x) {
                    $query->where('brand_id', $x);
                });
            };
            if (isset($formFilter['model_id'])) {
                $posts = $posts->
                when($formFilter['model_id'], function ($query, $x) {
                    $query->where('model_id', $x);
                });
            };
            if (isset($formFilter['body_type_id'])) {
                $posts = $posts->
                when($formFilter['body_type_id'], function ($query, $x) {
                    $query->where('body_type_id', $x);
                });
            };
            if (isset($formFilter['transmission_id'])) {
                $posts = $posts->
                when($formFilter['transmission_id'], function ($query, $x) {
                    $query->where('transmission_id', $x);
                });
            };
            if (isset($formFilter['fuel_id'])) {
                $posts = $posts->
                when($formFilter['fuel_id'], function ($query, $x) {
                    $query->where('fuel_id', $x);
                });
            }
            if (isset($formFilter['wheel_id'])) {
                $posts = $posts->
                when($formFilter['wheel_id'], function ($query, $x) {
                    $query->where('wheel_id', $x);
                });
            }
            if (isset($formFilter['drive_id'])) {
                $posts = $posts->
                when($formFilter['drive_id'], function ($query, $x) {
                    $query->where('drive_id', $x);
                });
            }
            if (isset($formFilter['color_id'])) {
                $posts = $posts->
                when($formFilter['color_id'], function ($query, $x) {
                    $query->where('color_id', $x);
                });
            }

            if (isset($formFilter['price_ot'])) {
                when($formFilter['price_ot'], function ($query, $x) {
                    $query->where('price', '>=', $x);
                });
            };
            if (isset($formFilter['price_do'])) {
                $posts = $posts->
                when($formFilter['price_do'], function ($query, $x) {
                    $query->where('price', '<=', $x);
                });
            };
            if (isset($formFilter['year_ot'])) {
                $posts = $posts->
                when($formFilter['year_ot'], function ($query, $x) {
                    $query->where('year_release', '>=', $x);
                });
            };
            if (isset($formFilter['year_do'])) {
                $posts = $posts->
                when($formFilter['year_do'], function ($query, $x) {
                    $query->where('year_release', '<=', $x);
                });
            };
            if (isset($formFilter['mileage_ot'])) {
                $posts = $posts->
                when($formFilter['mileage_ot'], function ($query, $x) {
                    $query->where('mileage', '>=', $x);
                });
            };
            if (isset($formFilter['mileage_do'])) {
                $posts = $posts->
                when($formFilter['mileage_do'], function ($query, $x) {
                    $query->where('mileage', '<=', $x);
                });
            };
            // сортировка есть
            if ($request->formOrdering) {
                $posts = $posts->orderBy(
                    $request->formOrdering['ordering_value'],
                    $request->formOrdering['ordering_direction'],
                )->paginate($paginate)->withQueryString();
                // сортировки нет
            } else {
                $posts = $posts->latest()->paginate($paginate)->withQueryString();
            }
        } else {
            // сортировка есть
            if ($request->formOrdering) {
//                dd($request->formOrdering);
                $posts = $posts->orderBy(
                    $request->formOrdering['ordering_value'],
                    $request->formOrdering['ordering_direction'],
                )->paginate($paginate)->withQueryString();
                // сортировки нет
            } else {
                $posts = $posts->latest()->paginate($paginate)->withQueryString();
            }
        }
    }
}
//if ($formFilter['brand_id']) {
//    $posts = $posts->
//    when($formFilter['brand_id'], function ($query, $x) {
//        $query->where('brand_id', $x);
//    });
//}
//if ($formFilter['model_id']) {
//    $posts = $posts->
//    when($formFilter['model_id'], function ($query, $x) {
//        $query->where('model_id', $x);
//    });
//}
//if ($formFilter['body_type_id']) {
//    $posts = $posts->
//    when($formFilter['body_type_id'], function ($query, $x) {
//        $query->where('body_type_id', $x);
//    });
//}
//if ($formFilter['transmission_id']) {
//    $posts = $posts->
//    when($formFilter['transmission_id'], function ($query, $x) {
//        $query->where('transmission_id', $x);
//    });
//}
//if ($formFilter['fuel_id']) {
//    $posts = $posts->
//    when($formFilter['fuel_id'], function ($query, $x) {
//        $query->where('fuel_id', $x);
//    });
//}
//if ($formFilter['wheel_id']) {
//    $posts = $posts->
//    when($formFilter['wheel_id'], function ($query, $x) {
//        $query->where('wheel_id', $x);
//    });
//}
//if ($formFilter['drive_id']) {
//    $posts = $posts->
//    when($formFilter['drive_id'], function ($query, $x) {
//        $query->where('drive_id', $x);
//    });
//}
//if ($formFilter['color_id']) {
//    $posts = $posts->
//    when($formFilter['color_id'], function ($query, $x) {
//        $query->where('color_id', $x);
//    });
//}
//if ($formFilter['price_ot']) {
//    $posts = $posts->
//    when($formFilter['price_ot'], function ($query, $x) {
//        $query->where('price', '>=', $x);
//    });
//}
//if ($formFilter['price_do']) {
//    $posts = $posts->
//    when($formFilter['price_do'], function ($query, $x) {
//        $query->where('price', '<=', $x);
//    });
//}
//if ($formFilter['year_ot']) {
//    $posts = $posts->
//    when($formFilter['year_ot'], function ($query, $x) {
//        $query->where('year_release', '>=', $x);
//    });
//}
//if ($formFilter['year_do']) {
//    $posts = $posts->
//    when($formFilter['year_do'], function ($query, $x) {
//        $query->where('year_release', '<=', $x);
//    });
//}
//if ($formFilter['mileage_ot']) {
//    $posts = $posts->
//    when($formFilter['mileage_ot'], function ($query, $x) {
//        $query->where('mileage', '>=', $x);
//    });
//}
//if ($formFilter['mileage_do']) {
//    $posts = $posts->
//    when($formFilter['mileage_do'], function ($query, $x) {
//        $query->where('mileage', '<=', $x);
//    });
//}
