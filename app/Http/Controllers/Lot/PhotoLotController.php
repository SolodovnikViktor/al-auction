<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use App\Models\Photo;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class PhotoLotController extends Controller
{
    public function store(Request $request, Lot $lot)
    {
        $userId = auth()->id();
        if ($request->hasFile('photoFilePond')) {
            $photo = $request->file('photoFilePond');
            $filename = Str::random() . '.webp';
            $folder = '/images/tmp/UserID-' . $userId . '/' . uniqid('image-', true);
            $photoTmp = Image::read($photo)->scaleDown(1200, 900)->toWebp(70);
            if (Storage::put($folder . '/' . $filename, $photoTmp)) {
                $size = Storage::size($folder . '/' . $filename);
                $photo = Photo::create([
                    'user_id' => $userId,
                    'lot_id' => $lot->id,
                    'name' => $filename,
                    'folder' => $folder,
                    'path' => '/storage' . $folder . '/' . $filename,
                    'size' => $size,
                ]);
                if ($photoPosition = PhotoPosition::where('lot_id', $lot->id)->whereNot('position', '')->first()) {
                    $photoPosition->update([
                        'position' => $photoPosition->position . ',' . $photo->id,
                    ]);
                } elseif ($photoPosition = PhotoPosition::where('lot_id', $lot->id)->where('position', '')->first()) {
                    $photoPosition->update([
                        'position' => $photoPosition->position . $photo->id,
                    ]);
                } else {
                    PhotoPosition::create([
                            'user_id' => $userId,
                            'lot_id' => $lot->id,
                            'position' => $photo->id,
                        ]
                    );
                }
                return $photo->id;
            }
        }
//        $request->session()->put('message_form', 'Автомобиль успешно добавлен12');
//        session(['message_form' => 'value']);
//        $request->session()->forget('message_form');
//        return App::abort(403, 'Файл не загружен.');
        return response()->json(['message' => 'Record not found.'], 404);
    }

    public function restore(Lot $lot)
    {
        if ($photoPosition = PhotoPosition::where('lot_id', $lot->id)->whereNot('position', '')->first()) {
            $photoPositionArr = explode(',', $photoPosition->position);
            return Photo::whereIn('id', $photoPositionArr)->orderByRaw("FIELD (id, $photoPosition->position) ASC")->get(
            );
        }
        return [];
    }

    public function reorder(Request $request, Lot $lot)
    {
        $photoPosition = PhotoPosition::where('lot_id', $lot->id)->first();
        $photoPosition->update(['position' => $request->getContent()]);
    }

    public function destroy(Request $request, Lot $lot)
    {
        $image = $request->getContent();
        $photoPosition = PhotoPosition::where('lot_id', $lot->id)->first();
        $photo = Photo::where('id', $image)->orWhere('path', $image)->first();
        $tmpReorderId = str_replace($photo->id, '', $photoPosition->position);
        $tmpReorderId = preg_replace('/,{2,}/', ',', trim($tmpReorderId, ','));
        $photoPosition->update(['position' => $tmpReorderId]);
        Storage::deleteDirectory($photo->folder);
        $photo->delete();
        return $photo->id;
    }
}
