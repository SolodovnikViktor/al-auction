<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    /**
     * @param Request $request
     * @param $lots
     * @param $user
     * @return void
     */
    public function getLots(Request $request, &$lots, &$user): void
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

        $lots = new Lot();
        if ($request->formSearch) {
            $lots = $lots->when($request->formSearch['search'], function ($query, $search) {
                $query->where('vin', 'like', '%' . $search . '%')
                    ->OrWhere('id', 'like', '%' . $search . '%');
            });
        }
        if ($request->formFilter) {
            $formFilter = $request->formFilter;
            if (isset($formFilter['brand_id'])) {
                $lots = $lots->
                when($formFilter['brand_id'], function ($query, $x) {
                    $query->where('brand_id', $x);
                });
            };
            if (isset($formFilter['model_id'])) {
                $lots = $lots->
                when($formFilter['model_id'], function ($query, $x) {
                    $query->where('model_id', $x);
                });
            };
            if (isset($formFilter['body_type_id'])) {
                $lots = $lots->
                when($formFilter['body_type_id'], function ($query, $x) {
                    $query->where('body_type_id', $x);
                });
            };
            if (isset($formFilter['transmission_id'])) {
                $lots = $lots->
                when($formFilter['transmission_id'], function ($query, $x) {
                    $query->where('transmission_id', $x);
                });
            };
            if (isset($formFilter['fuel_id'])) {
                $lots = $lots->
                when($formFilter['fuel_id'], function ($query, $x) {
                    $query->where('fuel_id', $x);
                });
            }
            if (isset($formFilter['wheel_id'])) {
                $lots = $lots->
                when($formFilter['wheel_id'], function ($query, $x) {
                    $query->where('wheel_id', $x);
                });
            }
            if (isset($formFilter['drive_id'])) {
                $lots = $lots->
                when($formFilter['drive_id'], function ($query, $x) {
                    $query->where('drive_id', $x);
                });
            }
            if (isset($formFilter['color_id'])) {
                $lots = $lots->
                when($formFilter['color_id'], function ($query, $x) {
                    $query->where('color_id', $x);
                });
            }


            if (isset($formFilter['price_ot'])) {
                $lots = $lots->
                when($formFilter['price_ot'], function ($query, $x) {
                    $query->where('price', '>=', $x);
                });
            };
            if (isset($formFilter['price_do'])) {
                $lots = $lots->
                when($formFilter['price_do'], function ($query, $x) {
                    $query->where('price', '<=', $x);
                });
            };

            if (isset($formFilter['year_ot'])) {
                $lots = $lots->
                when($formFilter['year_ot'], function ($query, $x) {
                    $query->where('year_release', '>=', $x);
                });
            };
            if (isset($formFilter['year_do'])) {
                $lots = $lots->
                when($formFilter['year_do'], function ($query, $x) {
                    $query->where('year_release', '<=', $x);
                });
            };


            if (isset($formFilter['mileage_ot'])) {
                $lots = $lots->
                when($formFilter['mileage_ot'], function ($query, $x) {
                    $query->where('mileage', '>=', $x);
                });
            };
            if (isset($formFilter['mileage_do'])) {
                $lots = $lots->
                when($formFilter['mileage_do'], function ($query, $x) {
                    $query->where('mileage', '<=', $x);
                });
            };
            if (isset($formFilter['horsepower_ot'])) {
                $lots = $lots->
                when($formFilter['horsepower_ot'], function ($query, $x) {
                    $query->where('horsepower', '>=', $x);
                });
            };
            if (isset($formFilter['horsepower_do'])) {
                $lots = $lots->
                when($formFilter['horsepower_do'], function ($query, $x) {
                    $query->where('horsepower', '<=', $x);
                });
            };
            // сортировка есть
        }
        if ($request->formOrdering) {
            $lots = $lots->orderBy(
                $request->formOrdering['ordering_value'],
                $request->formOrdering['ordering_direction'],
            )->paginate($paginate)->withQueryString();
            // сортировки нет
        } else {
            $lots = $lots->latest()->paginate($paginate)->withQueryString();
        }
    }
}
