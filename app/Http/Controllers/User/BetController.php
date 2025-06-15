<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Post\AdminPostIndexResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BetController extends Controller
{
    public function index(Request $request)
    {
        $this->getPosts($request, $posts, $user);

        return Inertia::render('Main/Bet/Index', [
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

    public function test(Request $request)
    {
        $request->session()->flash('message_form', 'Автомобиль успешно добавлен message_form');
        return back()->with('message', 'Category Created Successfully');
    }
}
