<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    /**
     * @param Request $request
     * @param $posts
     * @param $user
     * @return void
     */
    public function getPosts(Request $request, &$posts, &$user): void
    {
        $paginate = 15;
        if (auth()->user()) {
            $user = auth()->user();
            if (auth()->user()->catalog_view) {
                $paginate = 25;
            }
        } else {
            $user = DB::table('sessions')->where('id', auth()->getSession()->id())->first();
            if ($user->catalog_view) {
                $paginate = 25;
            }
        }

        $posts = new Post();
        if ($request->formSearch) {
            $posts = $posts->when($request->formSearch['search'], function ($query, $search) {
                $query->where('vin', 'like', '%' . $search . '%')
                    ->OrWhere('id', 'like', '%' . $search . '%');
            });
        }
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
                $posts = $posts->
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
            if (isset($formFilter['horsepower_ot'])) {
                $posts = $posts->
                when($formFilter['horsepower_ot'], function ($query, $x) {
                    $query->where('horsepower', '>=', $x);
                });
            };
            if (isset($formFilter['horsepower_do'])) {
                $posts = $posts->
                when($formFilter['horsepower_do'], function ($query, $x) {
                    $query->where('horsepower', '<=', $x);
                });
            };
            // сортировка есть
        }
        if ($request->formOrdering) {
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
