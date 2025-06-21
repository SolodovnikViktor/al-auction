<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Http\Requests\LotRequest;
use App\Http\Resources\AdminLotShowResource;
use App\Http\Resources\LotIndexResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Lot;
use App\Models\Photo;
use App\Models\PhotoPosition;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class AdminLotController extends Controller
{
    public function index(Request $request): Response
    {
        $this->getLots($request, $lots, $user);

        return Inertia::render('Admin/Lot/Index', [
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
        return Inertia::render('Admin/Lot/Show', [
            'lot' => new AdminLotShowResource($lot),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Lot/Create', [
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),
        ]);
    }

    public function storeBrand(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|unique:brands|max:20|min:2',
        ], [
            'title.required' => 'Заполните поле "Бренд".',
            'title.unique' => 'Такой Бренд уже создан.',
            'title.max' => 'Максимум 20 символов.',
            'title.min' => 'Минимум 2 символа.',
        ]);
        Brand::create($validated);
        return Redirect::back();
    }

    public function storeModel(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:20',
            'brand_id' => 'required|integer|exists:brands,id',
        ], [
            'title.required' => 'Заполните поле "Модель".',
            'title.max' => 'Максимум 20 символов.',
        ]);
        CarModel::create($validated);
        return Redirect::back();
    }

    public function store(LotRequest $request)
    {
        $userId = auth()->id();
        $validated = $request->validated();
        $lot = Lot::create($validated);

        if ($photoPositionStr = PhotoPosition::where('user_id', $userId)->where('lot_id', null)->first()) {
            $photoPositionArr = explode(',', $photoPositionStr->position);
            $photos = Photo::whereIn('id', $photoPositionArr)->get();
            foreach ($photos as $photo) {
                $photoName = $photo->name;
                $folder = '/images/lots/LotID-' . $lot->id . '/' . uniqid('image-', true);
                $pathTmp = $photo->folder . '/' . $photoName;
                $pathNew = $folder . '/' . $photoName;
                $pathNewMin = $folder . '/' . 'min_' . $photoName;
                if (Storage::copy($pathTmp, $pathNew)) {
                    $photoMin = Image::read(Storage::get($pathTmp))->scaleDown(
                        300,
                        200
                    );
                    $photoMin->save('storage/' . $pathNewMin);
                    Storage::deleteDirectory($photo->folder);
                    $photo->update([
                        'lot_id' => $lot->id,
                        'folder' => $folder,
                        'path' => '/storage' . $pathNew,
                        'path_min' => '/storage' . $pathNewMin,
                    ]);
                    $photoPositionStr->update([
                        'lot_id' => $lot->id,
                    ]);
                } else {
                    return back()->with('message_form', 'Фотографии не сохранены');
                }
            }
        }
        $request->session()->flash('message_form', 'Автомобиль успешно добавлен message_form');
        return to_route('admin-lot.show', $lot)->with('message', 'Category Created Successfully');
    }


    public function edit(Lot $lot): Response
    {
        return Inertia::render('Admin/Lot/Edit', [
            'lot' => $lot,
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),
        ]);
    }

    public function updatePublished(Request $request, Lot $lot)
    {
        $validated = $request->validate(['is_published' => ['required']]);
        $lot->update($validated);
        $request->session()->flash('message', 'Видимость изменена');
    }

    public function update(LotRequest $request, Lot $lot): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $lot->update($validated);

        $positionStr = PhotoPosition::where('lot_id', $lot->id)->first();
        $positionArr = explode(',', $positionStr->position);
        $photos = Photo::whereIn('id', $positionArr)->where('path_min', null)->get();
        foreach ($photos as $photo) {
            $photoName = $photo->name;
            $folder = '/images/lots/LotID-' . $lot->id . '/' . uniqid('image-', true);
            $pathTmp = $photo->folder . '/' . $photoName;
            $pathNew = $folder . '/' . $photoName;
            $pathNewMin = $folder . '/' . 'min_' . $photoName;
            if (Storage::copy($pathTmp, $pathNew)) {
                $photoMin = Image::read(Storage::get($pathNew))->scaleDown(
                    300,
                    200
                );
                $photoMin->save('storage/' . $pathNewMin);
                Storage::deleteDirectory($photo->folder);
                $photo->update([
                    'folder' => $folder,
                    'path' => '/storage' . $pathNew,
                    'path_min' => '/storage' . $pathNewMin,
                ]);
            } else {
//                return response()->json(['errors' => 'Error msg'], 404);
                return back()->with('message_form', 'Фотографии не сохранены');
            }
        }
        return to_route('admin-lot.show', $lot)->with('message', 'Обновлено');
    }

    public function destroy(Lot $lot)
    {
        $images = Photo::where('lot_id', $lot->id)->get();
        foreach ($images as $image) {
            Storage::deleteDirectory($image->folder);
            $image->delete();
        }
        $lot->delete();
        return to_route('admin-lot.index')->with('message', 'Объявление удалено');
    }
}
