<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\BodyType;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Photo;
use App\Models\Post;
use App\Models\PhotoPosition;
use App\Models\Transmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;
use function Laravel\Prompts\error;

class AdminPostController extends Controller
{
    public function index(): Response
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
        $photoPosition = PhotoPosition::where('user_id', $userId)->where('post_id', null)->firstOrFail();
        $photoPositionArr = explode(',', $photoPosition->position);

        //
        $imagePosition = '';
        //
        $photos = Photo::whereIn('id', $photoPositionArr)->get();

        foreach ($photos as $photo) {
            $photoName = $photo->name;
            $folder = 'PostID-' . $post->id . '/' . uniqid('image-', true);
            $pathTmp = $photo->folder . '/' . $photoName;
            $pathNew = '/images/posts/' . $folder . '/' . $photoName;
            $pathNewMin = '/images/posts/' . $folder . '/' . 'min_' . $photoName;

            if (Storage::copy($pathTmp, $pathNew)) {
                $photoMin = Image::read(Storage::get($pathTmp))->scaleDown(
                    300,
                    200
                );
                $photoMin->save('storage/' . $pathNewMin);

                $photo->update([
                    'post_id' => $post->id,
                    'folder' => $folder,
                    'path' => '/storage' . $pathNew,
                    'path_min' => '/storage' . $pathNewMin,
                ]);
//                DB::table('temporary_reorder')->where('userId', $userId)->delete();
                Storage::deleteDirectory($photo->folder);
                $photo->delete();
                if ($imagePosition === '') {
                    $imagePosition = $image->id;
                } else {
                    $imagePosition = $imagePosition . ',' . $image->id;
                }
            } else {
//                return response()->json(['errors' => 'Error msg'], 404);
                return back()->with('message_form', 'Фотографии не сохранены');
            }
//            Storage::copy($pathTmp, $pathNew);
        }
        $post->image_position = $imagePosition;
        $post->save();
        DB::table('temporary_reorder')->where('user_id', $userId)->delete();
        $request->session()->flash('message_form', 'Автомобиль успешно добавлен message_form');
        return to_route('admin-post.index')->with('message', 'Category Created Successfully');
    }

    public function show(Post $post): Response
    {
        return Inertia::render('Admin/Show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post): Response
    {
//        $post = PostResource::collection(Post::first());

        $userId = auth()->id();
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
        $validated = $request->validate([
            'is_published' => ['required'],
        ]);
        $post->update($validated);
        $request->session()->flash('message', 'Видимость изменена');
    }

    public function update(PostRequest $request, Post $post): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $post->update($validated);


        $imagePosition = $post->image_position;
        $photos = PhotoPosition::whereIn('id', $request->images_arr)->get();
        foreach ($photos as $photo) {
            $photoName = $photo->name;
            $folder = 'PostID-' . $post->id . '/' . uniqid('image-', true);
            $pathTmp = $photo->folder . '/' . $photoName;
            $pathNew = '/images/posts/' . $folder . '/' . $photoName;
            $pathNewMin = '/images/posts/' . $folder . '/' . 'min_' . $photoName;

            if (Storage::copy($pathTmp, $pathNew)) {
                $photoMin = \Intervention\Image\Laravel\Facades\Image::read(Storage::get($pathTmp))->scaleDown(
                    300,
                    200
                );
                $photoMin->save('storage/' . $pathNewMin);

                $image = Photo::create([
                    'post_id' => $post->id,
                    'name' => $photoName,
                    'folder' => $folder,
                    'path' => '/storage' . $pathNew,
                    'path_min' => '/storage' . $pathNewMin,
                    'size' => $photo->size,
                ]);
//                DB::table('temporary_reorder')->where('userId', $userId)->delete();
                Storage::deleteDirectory($photo->folder);
                $photo->delete();
                if ($imagePosition === '') {
                    $imagePosition = $image->id;
                } else {
                    $imagePosition = str_replace($photo->id, $image->id, $imagePosition);
                }
            } else {
//                return response()->json(['errors' => 'Error msg'], 404);
                return back()->with('message_form', 'Фотографии не сохранены');
            }
//            Storage::copy($pathTmp, $pathNew);
        }
        $post->update(['image_position' => $imagePosition]);

//        $post->image_position = $imagePosition;
//        $post->save();

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
        return to_route('admin-post.index')->with('message', 'Объявление удалено');
    }
}
