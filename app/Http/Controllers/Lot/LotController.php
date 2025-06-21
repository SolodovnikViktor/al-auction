<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Http\Resources\LotIndexResource;
use App\Http\Resources\LotShowResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Lot;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LotController extends Controller
{
    public function index(Request $request): Response
    {
        $this->getLots($request, $lots, $user);

        return Inertia::render('Main/Lot/Index', [
            'lots' => LotIndexResource::collection($lots),
            'user' => $user,
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

    public function show(Lot $lot): Response
    {
        return Inertia::render('Main/Lot/Show', [
            'lot' => new LotShowResource($lot),
        ]);
    }

    public function getModel(Request $request)
    {
        return CarModel::where('brand_id', $request->getContent())->get();
    }

    public function filterCount(Request $request)
    {
        $this->getLots($request, $lots, $user);
        return $lots->total();
    }
}
