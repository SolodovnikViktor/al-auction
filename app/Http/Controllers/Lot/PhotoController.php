<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class PhotoController extends Controller
{
    public function store(Request $request)
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
                    'name' => $filename,
                    'folder' => $folder,
                    'path' => '/storage' . $folder . '/' . $filename,
                    'size' => $size,
                ]);
                if ($photoPosition = PhotoPosition::where('user_id', $userId)->where('lot_id', null)
                    ->whereNot('position', '')->first()) {
                    $photoPosition->update([
                        'position' => $photoPosition->position . ',' . $photo->id,
                    ]);
                } elseif ($photoPosition = PhotoPosition::where('user_id', $userId)->where('lot_id', null)
                    ->where('position', '')->first()) {
                    $photoPosition->update([
                        'position' => $photoPosition->position . $photo->id,
                    ]);
                } else {
                    PhotoPosition::create([
                        'user_id' => $userId,
                        'position' => $photo->id,
                    ]);
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

    public function restore()
    {
        $userId = auth()->id();
        if ($photoPosition = PhotoPosition::where('user_id', $userId)->where('lot_id', null)
            ->whereNot('position', '')->first()) {
            $photoPositionArr = explode(',', $photoPosition->position);
            return Photo::whereIn('id', $photoPositionArr)->orderByRaw("FIELD (id, $photoPosition->position) ASC")->get(
            );
        }
        return [];
    }

    public function reorder(Request $request)
    {
        $userId = auth()->id();
        $photoPosition = PhotoPosition::where('user_id', $userId)->where('lot_id', null)->first();
        $photoPosition->update(['position' => $request->getContent()]);
    }

    public function destroy()
    {
        $userId = auth()->id();
        $getPath = request()->getContent();
        $photo = Photo::where('id', $getPath)->orWhere('path', $getPath)->first();
        $photoPosition = PhotoPosition::where('user_id', $userId)->where('lot_id', null)->first();
        $position = str_replace($photo->id, '', $photoPosition->position);
        $position = preg_replace('/,{2,}/', ',', trim($position, ','));
        $photoPosition->update(['position' => $position]);
        Storage::deleteDirectory($photo->folder);
        $photo->delete();
        return $photo->id;
    }
}
