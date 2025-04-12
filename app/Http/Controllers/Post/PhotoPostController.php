<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;

use App\Models\Photo;
use App\Models\Post;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoPostController extends Controller
{
    public function store(Request $request)
    {
        $userId = auth()->id();
        if ($request->hasFile('imageFilePond')) {
            $image = $request->file('imageFilePond');
            $filename = Str::random() . '.webp';
            $folder = '/images/tmp/' . 'UserID-' . $userId . '/' . uniqid('image-', true);
            $imageTmp = \Intervention\Image\Laravel\Facades\Image::read($image)->scaleDown(1200, 900)->toWebp(70);
            if (Storage::put($folder . '/' . $filename, $imageTmp)) {
                $size = Storage::size($folder . '/' . $filename);
                $imageId = PhotoPosition::create([
                    'path' => '/storage' . $folder . '/' . $filename,
                    'folder' => $folder,
                    'name' => $filename,
                    'user_id' => $userId,
                    'size' => $size,
                ]);

                if ($tmpReorder = DB::table('temporary_reorder')->where('user_id', $userId)
                    ->value('position')) {
                    DB::table('temporary_reorder')->where('user_id', $userId)->delete();
                    DB::table('temporary_reorder')->insert([
                        'user_id' => $userId,
                        'position' => $tmpReorder . ',' . $imageId->id,
                    ]);
                } else {
                    DB::table('temporary_reorder')->where('user_id', $userId)->delete();
                    DB::table('temporary_reorder')->insert([
                        'user_id' => $userId,
                        'position' => $imageId->id,
                    ]);
                }
                return $imageId->id;
            }
        }
//        $request->session()->put('message_form', 'Автомобиль успешно добавлен12');
//        session(['message_form' => 'value']);
//        $request->session()->forget('message_form');
//        return App::abort(403, 'Файл не загружен.');
        return response()->json(['message' => 'Record not found.'], 404);
    }

    public function postStore(Request $request, Post $post)
    {
        $userId = auth()->id();
        if ($request->hasFile('imageFilePond')) {
            $image = $request->file('imageFilePond');
            $filename = Str::random() . '.webp';
            $folder = '/images/tmp/' . 'UserID-' . $userId . '/' . uniqid('image-', true);
            $imageTmp = \Intervention\Image\Laravel\Facades\Image::read($image)->scaleDown(1200, 900)->toWebp(70);
            if (Storage::put($folder . '/' . $filename, $imageTmp)) {
                $size = Storage::size($folder . '/' . $filename);
                $imageId = PhotoPosition::create([
                    'path' => '/storage' . $folder . '/' . $filename,
                    'folder' => $folder,
                    'name' => $filename,
                    'user_id' => $userId,
                    'size' => $size,
                ]);
                if ($post->image_position !== '') {
                    $post->image_position = $post->image_position . ',' . $imageId->id;
                } else {
                    $post->image_position = $imageId->id;
                }
                $post->save();
                return $imageId->id;
            }
        }
//        $request->session()->put('message_form', 'Автомобиль успешно добавлен12');
//        session(['message_form' => 'value']);
//        $request->session()->forget('message_form');
//        return App::abort(403, 'Файл не загружен.');
        return response()->json(['message' => 'Файл не загружен.'], 404);
    }

    public function restore()
    {
        $userId = auth()->id();
        $tmpFile = PhotoPosition::where('user_id', $userId)->get();

        if ($tmpReorder = DB::table('temporary_reorder')->where('user_id', $userId)->first()) {
            if ($tmpReorder->position !== '') {
                $tmpReorderArr = explode(',', $tmpReorder->position);
                $tmpImages = PhotoPosition::whereIn('id', $tmpReorderArr)->orderByRaw(
                    "FIELD (id, $tmpReorder->position) ASC"
                )->get();
                return $tmpImages;
//                return $tmpImages->merge($tmpFile);
            }
        }
//        $request->session()->flash('message', 'Видимость изменена');
        return $tmpFile;
    }

    public function postRestore(Post $post)
    {
        $positionArr = explode(',', $post->image_position);
        if ($post->image_position) {
            $postImages = Photo::whereIn('id', $positionArr)->orderByRaw("FIELD (id, $post->image_position) ASC")->get();
            if (count($positionArr) > $postImages->count()) {
                $tmpImages = PhotoPosition::whereIn('id', $positionArr)->orderByRaw(
                    "FIELD (id, $post->image_position) ASC"
                )->get();
                return $postImages->merge($tmpImages);
            } else {
                return $postImages;
            }

        }
        return Photo::where('post_id', $post->id)->get();
    }


    public function reorder(Request $request)
    {
        $userId = auth()->id();
        DB::table('temporary_reorder')->where('user_id', $userId)->delete();
        DB::table('temporary_reorder')->insert([
            'user_id' => $userId,
            'position' => $request->getContent(),
        ]);

//        $tmpImages = PhotoPosition::where('user_id', $userId)->get();
//    return response()->json($tmpImages);
        return [];
    }

    public function postReorder(Request $request, Post $post)
    {
//        $positionArr = explode(',', $request->getContent());
//        $positionStr = $request->getContent();
//        $images = Photo::whereIn('id', $positionArr)->orderByRaw(
//            "FIELD (id, $positionStr) ASC"
//        )->select('id')->get();
//        $images = Photo::where('post_id', $post->id)->get();
//        return $images;
//        $tic = 0;

//        dd($request->getContent(), $positionArr, $images);

//        foreach ($images as $keyImage => $image) {
//            foreach ($positionArr as $keyPosition => $position) {
//                if ($keyImage == $keyPosition) {
//                    $image->id = $position;
//                    $image->save();
//                }
//            }
//        }
        $post->update(['image_position' => $request->getContent()]);
//        $post->image_position = $request->getContent();
//        $post->save();
    }

    public function destroy()
    {
        $userId = auth()->id();
        $image = request()->getContent();
        $tmpFile = PhotoPosition::where('id', $image)->orWhere('path', $image)->first();
        if ($tmpReorder = DB::table('temporary_reorder')->where('user_id', $userId)->first()) {
            $tmpReorderId = str_replace($tmpFile->id, '', $tmpReorder->position);
            $tmpReorderId = preg_replace('/,{2,}/', ',', trim($tmpReorderId, ','));

            DB::table('temporary_reorder')->where('user_id', $userId)->update([
                'position' => $tmpReorderId,
            ]);
        }
        if ($tmpFile) {
            Storage::deleteDirectory($tmpFile->folder);
            $tmpFile->delete();
        }
        return $tmpFile->id;
    }

    public function postDestroy(Request $request, Post $post)
    {
        $image = $request->getContent();

        if (PhotoPosition::where('id', $image)->orWhere('path', $image)->first()) {
            $tmpImage = PhotoPosition::where('id', $image)->orWhere('path', $image)->first();
        }
        if (Photo::where('path', $image)->first()) {
            $tmpImage = Photo::where('path', $image)->first();
        }
        $tmpReorderId = str_replace($tmpImage->id, '', $post->image_position);
        $tmpReorderId = preg_replace('/,{2,}/', ',', trim($tmpReorderId, ','));
        $post->update(['image_position' => $tmpReorderId]);
        Storage::deleteDirectory($tmpImage->folder);
        $tmpImage->delete();
        return $tmpImage->id;
    }
}
