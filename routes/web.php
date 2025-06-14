<?php

use App\Http\Controllers\Post\AdminPostController;
use App\Http\Controllers\Post\AdminPostIndexController;
use App\Http\Controllers\Post\MainPostController;
use App\Http\Controllers\Post\MainPostIndexController;
use App\Http\Controllers\Post\PhotoController;
use App\Http\Controllers\Post\PhotoPostController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\AdminUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main/Home');
})->name('home');

Route::get('/contact', function () {
    return Inertia::render('Main/Contact');
})->name('contact');

Route::middleware(['auth', 'verified', 'roles:admin'])->group(function () {
    Route::get('/admin/posts/index', [AdminPostIndexController::class, 'index'])->name('admin-posts.index');
    Route::get('/admin/posts/index/filter', [AdminPostIndexController::class, 'index'])
        ->name('admin-posts.filter');
    Route::post('/admin/posts/filter', [AdminPostIndexController::class, 'filterCount']);
    Route::get('/admin/posts/search', [AdminPostIndexController::class, 'search'])->name('admin-post.search');

    Route::get('/admin/post/create', [AdminPostController::class, 'create'])->name('admin-post.create');
    Route::post('/admin/post/create', [AdminPostController::class, 'store'])->name('admin-post.store');
    Route::get('/admin/post/{post}/show', [AdminPostController::class, 'show'])->name('admin-post.show');
    Route::get('/admin/post/{post}/edit', [AdminPostController::class, 'edit'])->name('admin-post.edit');
    Route::patch('/admin/post/{post}', [AdminPostController::class, 'update'])->name('admin-post.update');
    Route::patch('/admin/post/update-published/{post}', [AdminPostController::class, 'updatePublished'])->name(
        'admin-post.updatePublished'
    );
    Route::delete('/admin/post/{post}', [AdminPostController::class, 'destroy'])->name('admin-post.destroy');

    Route::post('/admin/post/crete/brand', [AdminPostController::class, 'storeBrand'])->name('admin-post.storeBrand');
    Route::post('/admin/post/crete/model', [AdminPostController::class, 'storeModel'])->name('admin-post.storeModel');

    Route::post('/admin/post/create/tmp-upload', [PhotoController::class, 'store']);
    Route::get('/admin/post/create/tmp-restore', [PhotoController::class, 'restore']);
    Route::post('/admin/post/create/tmp-reorder', [PhotoController::class, 'reorder']);
    Route::delete('/admin/post/create/tmp-revert', [PhotoController::class, 'destroy']);
    Route::post('/admin/post/tmp-upload/{post}', [PhotoPostController::class, 'store']);
    Route::get('/admin/post/tmp-restore/{post}', [PhotoPostController::class, 'restore']);
    Route::post('/admin/post/tmp-reorder/{post}', [PhotoPostController::class, 'reorder']);
    Route::delete('/admin/post/tmp-revert/{post}', [PhotoPostController::class, 'destroy']);

    Route::get('/admin/users/index', [AdminUserController::class, 'index'])->name('admin-users.index');
    Route::get('/admin/user/{user}/show', [AdminUserController::class, 'show'])->name('admin-user.show');
    Route::get('/admin/user/{user}/edit', [AdminUserController::class, 'edit'])->name('admin-user.edit');
    Route::patch('/admin/update-role/{user}', [AdminUserController::class, 'updateRole']);
});

Route::get('/main/posts/index', [MainPostIndexController::class, 'index'])->name('main-posts.index');
Route::get('/main/posts/index/filter', [MainPostIndexController::class, 'index'])
    ->name('main-posts.filter');
Route::post('/main/posts/filter', [MainPostIndexController::class, 'filterCount']);
Route::get('/main/posts/search', [MainPostIndexController::class, 'search'])->name('main-post.search');
Route::get('/main/post/{post}/show', [MainPostController::class, 'show'])->name('main-post.show');

Route::post('/post/get-model', [PostController::class, 'getModel']);
Route::patch('/update-catalog-view', [ProfileController::class, 'updateCatalogView']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
