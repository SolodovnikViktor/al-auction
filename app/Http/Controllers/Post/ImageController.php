<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

use function Laravel\Prompts\error;

class ImageController extends Controller
{

    public function store(Request $request)
    {
        $userId = auth()->id();

        if ($request->hasFile('imageFilePond')) {
            $image = $request->file('imageFilePond');

            $filename = Str::random() . '.webp';
            $folder = '/images/tmp/' . 'UserID-' . $userId . '/' . uniqid('image-', true);

            $imageTmp = Image::read($image)->scaleDown(1200, 900)->toWebp(70);
//            $image->storeAs($folder, $filename);
            if (Storage::put($folder . '/' . $filename, $imageTmp)) {
                $size = Storage::size($folder . '/' . $filename);

                $imageId = TemporaryFile::create([
                    'fullFolder' => '/storage' . $folder . '/' . $filename,
                    'folder' => $folder,
                    'filename' => $filename,
                    'id_user' => $userId,
                    'size' => $size,
                ]);
                return $imageId->id;
            }
        }
//        $request->session()->put('message_form', 'Автомобиль успешно добавлен12');
//        session(['message_form' => 'value']);
//        $request->session()->forget('message_form');
//        return App::abort(403, 'Файл не загружен.');
        return response()->json([
            'message' => 'Record not found.'
        ], 404);
    }

//    public function restore()
//    {
//        dd('22');
//        $path = public_path('images/image-67d0b1dc25fa87.86451071/1.jpg');
//        $headers = [
//            'Content-Type' => 'image/jpeg',
//            'Content-Disposition' => 'inline; filename="1.jpg"'
//        ];
//        return response()->file($path, $headers);
//    }

public function restore()
{
    $userId = auth()->id();

    $tmpImages = TemporaryFile::where('id_user', $userId)->get();
//    return response()->json($tmpImages);
    return $tmpImages;

}

    public function destroy()
    {
        $image = request()->getContent();
        $temporaryImage = TemporaryFile::where('id', $image)->orWhere('fullFolder', $image)->first();
        if ($temporaryImage) {
            Storage::deleteDirectory($temporaryImage->folder);
            $temporaryImage->delete();
        }
        return '';
    }

}
