<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Admin\PostIndexResource;
use App\Http\Resources\Admin\PostShowResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Photo;
use App\Models\PhotoPosition;
use App\Models\Post;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class AdminPostController extends Controller
{
    public function index(): Response
    {
        $paginate = 15;
        if(auth()->user()->catalog_view){
            $paginate = 50;
        }

        $postsPaginate = Post::with('user', 'images', 'imagesPath')->paginate(1);
        $postsPaginateString = Post::with('user', 'images', 'imagesPath')->paginate(1)->withQueryString();
        return Inertia::render('Admin/Index', [
            'posts' => PostIndexResource::collection(Post::paginate($paginate)),
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),

            'postsPaginate' => $postsPaginate,
            'postsPaginateString' => $postsPaginateString,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Create', [
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),
        ]);
    }

    public function getModel(Request $request)
    {
        return CarModel::where('brand_id', $request->getContent())->get();
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
            'title.required' => 'Заполните поле "Бренд".',
            'title.unique' => 'Такой Бренд уже создан.',
            'title.max' => 'Максимум 20 символов.',
        ]);
        CarModel::create($validated);
        return Redirect::back();
    }

    public function store(PostRequest $request)
    {
        $userId = auth()->id();
        $validated = $request->validated();
        $post = Post::create($validated);

        if ($photoPositionStr = PhotoPosition::where('user_id', $userId)->where('post_id', null)->first()) {
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
        }
        $request->session()->flash('message_form', 'Автомобиль успешно добавлен message_form');
        return to_route('admin-post.show', $post)->with('message', 'Category Created Successfully');
    }

    public function show(Post $post): Response
    {
        return Inertia::render('Admin/Show', [
            'post' => new PostShowResource($post),
        ]);
    }

    public function edit(Post $post): Response
    {
        return Inertia::render('Admin/Edit', [
            'post' => $post,
            'brands' => Brand::all(),
            'fuels' => Fuel::all(),
            'wheels' => Wheel::all(),
            'colors' => Color::all(),
            'drives' => Drive::all(),
            'bodyTypes' => BodyType::all(),
            'transmissions' => Transmission::all(),
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
    }

    public function destroy(Post $post)
    {
        $images = Photo::where('post_id', $post->id)->get();
        foreach ($images as $image) {
            Storage::deleteDirectory($image->folder);
            $image->delete();
        }
        $post->delete();
        return to_route('admin-posts.index')->with('message', 'Объявление удалено');
    }
}
