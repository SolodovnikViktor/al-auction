<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

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
            if (Storage::put($folder . '/' . $filename, $imageTmp)) {
                $size = Storage::size($folder . '/' . $filename);
                $imageId = TemporaryFile::create([
                    'path' => '/storage' . $folder . '/' . $filename,
                    'folder' => $folder,
                    'filename' => $filename,
                    'id_user' => $userId,
                    'size' => $size,
                ]);

                if ($tempReorder = DB::table('temporary_reorder')->where('userId', $userId)
                    ->value('position')){
                    DB::table('temporary_reorder')->where('userId', $userId)->delete();
                    DB::table('temporary_reorder')->insert([
                        'userId' => $userId,
                        'position' => $tempReorder . ',' . $imageId->id,
                    ]);
                }
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

    public function restore()
    {
        $tmpArr = [];
        $arrayTmp = [];
        $userId = auth()->id();
        $tmpFile = TemporaryFile::where('id_user', $userId)->get();

        if ($tempReorder = DB::table('temporary_reorder')->where('userId', $userId)->first()) {
            if ($tempReorder->position !== '') {
                $tempReorderArr = explode(',', $tempReorder->position);
//                dd($tempReorder);
                foreach ($tempReorderArr as $item) {
                    if ($tmpFile = TemporaryFile::where('id', $item)->first()) {
                        $tmpArr [] = $tmpFile;
                    }
                }
                foreach ($tmpArr as $array) {
                    $arrayTmp [] = $array;
                }
                return response()->json($arrayTmp);
            }
        }
        return $tmpFile;
    }

    public function destroy()
    {
        $userId = auth()->id();
        $image = request()->getContent();
        $tmpFile = TemporaryFile::where('id', $image)->orWhere('path', $image)->first();
        if($tmpReorder = DB::table('temporary_reorder')->where('userId', $userId)->first()){
            $tmpReorderUserId = str_replace($tmpFile->id, '', $tmpReorder->position);
            $tmpReorderUserId = ltrim($tmpReorderUserId, ',');
            $tmpReorderUserId = rtrim($tmpReorderUserId, ',');
            DB::table('temporary_reorder')->where('userId', $userId)->update([
                'position' => $tmpReorderUserId,
            ]);
        }

        if ($tmpFile) {
            Storage::deleteDirectory($tmpFile->folder);
            $tmpFile->delete();
        }
        return $tmpFile->id;
    }

    public function reorder(Request $request)
    {
        $userId = auth()->id();
        DB::table('temporary_reorder')->where('userId', $userId)->delete();
        DB::table('temporary_reorder')->insert([
            'userId' => $userId,
            'position' => $request->getContent(),
        ]);

//        $tmpImages = TemporaryFile::where('id_user', $userId)->get();
//    return response()->json($tmpImages);
        return [];
    }
}
