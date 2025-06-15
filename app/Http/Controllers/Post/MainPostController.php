<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Admin\Post\AdminPostIndexResource;
use App\Http\Resources\Admin\Post\AdminPostShowResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Photo;
use App\Models\PhotoPosition;
use App\Models\Post;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class MainPostController extends Controller
{
    public function index(Request $request): Response
    {
        $this->getPosts($request, $posts, $user);

        return Inertia::render('Main/Post/Index', [
            'posts' => AdminPostIndexResource::collection($posts),
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

    public function show(Post $post): Response
    {
        return Inertia::render('Main/Post/Show', [
            'post' => new AdminPostShowResource($post),
        ]);
    }

    public function update(PostRequest $request, Post $post): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $post->update($validated);

        $positionStr = PhotoPosition::where('post_id', $post->id)->first();
        $positionArr = explode(',', $positionStr->position);
        $photos = Photo::whereIn('id', $positionArr)->where('path_min', null)->get();
        foreach ($photos as $photo) {
            $photoName = $photo->name;
            $folder = '/images/posts/PostID-' . $post->id . '/' . uniqid('image-', true);
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
        return to_route('admin-post.show', $post)->with('message', 'Обновлено');
    }
}
