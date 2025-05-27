<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Admin\PostIndexResource;
use App\Http\Resources\Admin\PostShowResource;
use App\Http\Resources\User\UserResource;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Photo;
use App\Models\PhotoPosition;
use App\Models\Post;
use App\Models\Role;
use App\Models\Transmission;
use App\Models\User;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class AdminUserController extends Controller
{
    public function index(Request $request): Response
    {
        if($request->ordering_value){
            $users = User::orderBy($request->ordering_value, $request->ordering_direction)->paginate(20)->withQueryString();
        }else{
           $users = User::paginate(20);
        }
        return Inertia::render('Admin/Users/Index', [
//            'users' => UserResource::collection(User::paginate(20)),
            'users' => UserResource::collection($users),
            'roles' => Role::all(),
            'orderingValue' => $request->ordering_value,
            'orderingDirection' => $request->ordering_direction,
            'orderingDesc' => $request->ordering_desc,
            'orderingAsc' => $request->ordering_asc,
            'headerIndex' => $request->header_index,
        ]);
    }








    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate(['role_id' => ['required']]);
        $user->update($validated);
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

    public function show(User $user): Response
    {
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
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
