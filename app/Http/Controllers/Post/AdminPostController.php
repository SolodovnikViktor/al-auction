<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Admin\PostResource;
use App\Http\Resources\Admin\PostShowResource;
use App\Models\BodyType;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Photo;
use App\Models\PhotoPosition;
use App\Models\Post;
use App\Models\Transmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class AdminPostController extends Controller
{
    public function indexPhoto(): Response
    {
        $postsPaginate = Post::with('user', 'images', 'imagesPath')->paginate(15);
        $posts = PostResource::collection(Post::paginate(15));
        return Inertia::render('Admin/Index', [
            'posts' => $posts,
            'postsPaginate' => $postsPaginate,
        ]);
    }

    public function indexList(): Response
    {
        $postsPaginate = Post::with('user', 'images', 'imagesPath')->paginate(15);
        $posts = PostResource::collection(Post::paginate(15));
        return Inertia::render('Admin/Index', [
            'posts' => $posts,
            'postsPaginate' => $postsPaginate,
        ]);
    }

    public function create(): Response
    {
        $colors = Color::all()->select('id', 'title');
        $drives = Drive::all()->select('id', 'title');
        $bodyTypes = BodyType::all()->select('id', 'title');
        $transmissions = Transmission::all()->select('id', 'title');
        return Inertia::render('Admin/Create', [
            'colors' => $colors,
            'drives' => $drives,
            'bodyTypes' => $bodyTypes,
            'transmissions' => $transmissions,
        ]);
    }

    public function store(PostRequest $request)
    {
        $userId = auth()->id();
        $validated = $request->validated();
        $post = Post::create($validated);

        $photoPositionStr = PhotoPosition::where('user_id', $userId)->where('post_id', null)->firstOrFail();
        $photoPositionArr = explode(',', $photoPositionStr->position);
        $photos = Photo::whereIn('id', $photoPositionArr)->get();
        foreach ($photos as $photo) {
            $photoName = $photo->name;
            $folder = '/images/posts/PostID-' . $post->id . '/' . uniqid('image-', true);
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
                    'post_id' => $post->id,
                    'folder' => $folder,
                    'path' => '/storage' . $pathNew,
                    'path_min' => '/storage' . $pathNewMin,
                ]);
                $photoPositionStr->update([
                    'post_id' => $post->id,
                ]);
            } else {
                return back()->with('message_form', 'Фотографии не сохранены');
            }
        }
        $request->session()->flash('message_form', 'Автомобиль успешно добавлен message_form');
        return to_route('admin-post.show', $post)->with('message', 'Category Created Successfully');
    }

    public function show(Post $post): Response
    {
        $postRes = new PostShowResource($post);
        return Inertia::render('Admin/Show', [
            'postRes' => $postRes,
        ]);
    }

    public function edit(Post $post): Response
    {
        $colors = Color::all()->select('id', 'title');
        $drives = Drive::all()->select('id', 'title');
        $bodyTypes = BodyType::all()->select('id', 'title');
        $transmissions = Transmission::all()->select('id', 'title');
        return Inertia::render('Admin/Edit', [
            'post' => $post,
            'colors' => $colors,
            'drives' => $drives,
            'bodyTypes' => $bodyTypes,
            'transmissions' => $transmissions,
        ]);
    }

    public function updatePublished(Request $request, Post $post)
    {
        $validated = $request->validate(['is_published' => ['required']]);
        $post->update($validated);
        $request->session()->flash('message', 'Видимость изменена');
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
//        return redirect()->route('admin-post.index')->with('message', 'Category Updated Successfully');
    }

    public function destroy(Post $post)
    {
        $images = Photo::where('post_id', $post->id)->get();
        foreach ($images as $image) {
            Storage::deleteDirectory($image->folder);
            $image->delete();
        }
        $post->delete();
        return to_route('admin-post.indexPhoto')->with('message', 'Объявление удалено');
    }
}
