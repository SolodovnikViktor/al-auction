<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\BodyType;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Image;
use App\Models\Post;
use App\Models\TemporaryFile;
use App\Models\Transmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
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
//        $userId = auth()->id();
//        $filePositionId = DB::table('temporary_reorder')->where('userId', $userId)->select('position')->first();
        $colors = Color::all()->select('id', 'title');
        $drives = Drive::all()->select('id', 'title');
        $bodyTypes = BodyType::all()->select('id', 'title');
        $transmissions = Transmission::all()->select('id', 'title');
//        $tmpImages = TemporaryFile::where('id_user', $userId)->get();

        return Inertia::render('Admin/Create', [
            'colors' => $colors,
            'drives' => $drives,
            'bodyTypes' => $bodyTypes,
            'transmissions' => $transmissions,
//            'tmpImages' => $tmpImages,
//            'filePositionId' => $filePositionId,
        ]);
    }

    public function store(PostRequest $request)
    {
        $userId = auth()->id();
        $validated = $request->validated();
        $post = Post::create($validated);
        $imagePosition = '';

        $temporaryImages = TemporaryFile::whereIn('id', $request->images_arr)->get();

        foreach ($temporaryImages as $temporaryImage) {
            $imageName = $temporaryImage->name;
            $folder = 'PostID-' . $post->id . '/' . uniqid('image-', true);
            $pathTmp = $temporaryImage->folder . '/' . $imageName;
            $pathNew = '/images/posts/' . $folder . '/' . $imageName;
            $pathNewMin = '/images/posts/' . $folder . '/' . 'min_' . $imageName;

            if (Storage::copy($pathTmp, $pathNew)) {
                $imageMin = \Intervention\Image\Laravel\Facades\Image::read(Storage::get($pathTmp))->scaleDown(
                    300,
                    200
                );
                $imageMin->save('storage/' . $pathNewMin);

                $image = Image::create([
                    'post_id' => $post->id,
                    'name' => $imageName,
                    'folder' => $folder,
                    'path' => '/storage' . $pathNew,
                    'path_min' => '/storage' . $pathNewMin,
                    'size' => $temporaryImage->size,
                ]);
//                DB::table('temporary_reorder')->where('userId', $userId)->delete();
                Storage::deleteDirectory($temporaryImage->folder);
                $temporaryImage->delete();
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
        $temporaryImages = TemporaryFile::whereIn('id', $request->images_arr)->get();
        foreach ($temporaryImages as $temporaryImage) {
            $imageName = $temporaryImage->name;
            $folder = 'PostID-' . $post->id . '/' . uniqid('image-', true);
            $pathTmp = $temporaryImage->folder . '/' . $imageName;
            $pathNew = '/images/posts/' . $folder . '/' . $imageName;
            $pathNewMin = '/images/posts/' . $folder . '/' . 'min_' . $imageName;

            if (Storage::copy($pathTmp, $pathNew)) {
                $imageMin = \Intervention\Image\Laravel\Facades\Image::read(Storage::get($pathTmp))->scaleDown(
                    300,
                    200
                );
                $imageMin->save('storage/' . $pathNewMin);

                $image = Image::create([
                    'post_id' => $post->id,
                    'name' => $imageName,
                    'folder' => $folder,
                    'path' => '/storage' . $pathNew,
                    'path_min' => '/storage' . $pathNewMin,
                    'size' => $temporaryImage->size,
                ]);
//                DB::table('temporary_reorder')->where('userId', $userId)->delete();
                Storage::deleteDirectory($temporaryImage->folder);
                $temporaryImage->delete();
                if ($imagePosition === '') {
                    $imagePosition = $image->id;
                } else {
                    $imagePosition = str_replace($temporaryImage->id, $image->id, $imagePosition);
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
        $images = Image::where('post_id', $post->id)->get();
        foreach ($images as $image) {
            Storage::deleteDirectory($image->folder);
            $image->delete();
        }
        $post->delete();
        return to_route('admin-post.index')->with('message', 'Объявление удалено');
    }
}
