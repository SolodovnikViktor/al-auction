<?php

use App\Http\Controllers\Post\AdminPostController;
use App\Http\Controllers\Post\AdminPostFilterController;
use App\Http\Controllers\Post\AdminPostsIndexController;
use App\Http\Controllers\Post\PhotoController;
use App\Http\Controllers\Post\PhotoPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Users\AdminUserController;
use App\Http\Controllers\Users\AdminUserFilterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main/Home');
})->name('home');

Route::get('/contact', function () {
    return Inertia::render('Main/Contact');
})->name('contact');

Route::middleware(['auth', 'verified', 'roles:1'])->group(function () {
    Route::get('/admin/posts/index', [AdminPostsIndexController::class, 'index'])->name('admin-posts.index');
    Route::get('/admin/posts/index/filter', [AdminPostFilterController::class, 'adminPostsFilter'])
        ->name('admin-posts.filter');
    Route::get('/admin/post/create', [AdminPostController::class, 'create'])->name('admin-post.create');
    Route::post('/admin/post/create', [AdminPostController::class, 'store'])->name('admin-post.store');
    Route::get('/admin/post/{post}/show', [AdminPostController::class, 'show'])->name('admin-post.show');
    Route::get('/admin/post/{post}/edit', [AdminPostController::class, 'edit'])->name('admin-post.edit');
    Route::patch('/admin/post/{post}', [AdminPostController::class, 'update'])->name('admin-post.update');
    Route::patch('/admin/post/update-published/{post}', [AdminPostController::class, 'updatePublished'])->name(
        'admin-post.updatePublished'
    );
    Route::delete('/admin/post/{post}', [AdminPostController::class, 'destroy'])->name('admin-post.destroy');

    Route::post('/admin/post/crete/get-model', [AdminPostController::class, 'getModel']);
    Route::post('/admin/post/crete/brand', [AdminPostController::class, 'storeBrand'])->name('admin-post.storeBrand');
    Route::post('/admin/post/crete/model', [AdminPostController::class, 'storeModel'])->name('admin-post.storeModel');

    Route::get('/admin/posts/search', [AdminPostFilterController::class, 'adminSearch'])->name('admin-post.search');
    Route::post('/admin/posts/filter', [AdminPostFilterController::class, 'adminFilterCount']);


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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/update-catalog-view/{user}', [ProfileController::class, 'updateCatalogView']);
});

require __DIR__ . '/auth.php';
