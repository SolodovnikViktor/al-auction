<?php

use App\Http\Controllers\Post\AdminPostController;
use App\Http\Controllers\Post\AdminPostFilterController;
use App\Http\Controllers\Post\PhotoController;
use App\Http\Controllers\Post\PhotoPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main/Home');
})->name('home');

Route::get('/contact', function () {
    return Inertia::render('Main/Contact');
})->name('contact');

Route::middleware(['auth', 'verified', 'roles:admin'])->group(function () {
    Route::get('/admin/posts/photos', [AdminPostController::class, 'index'])->name('admin-post.index');
    Route::get('/admin/post/create', [AdminPostController::class, 'create'])->name('admin-post.create');
    Route::post('/admin/post/create', [AdminPostController::class, 'store'])->name('admin-post.store');
    Route::get('/admin/post/{post}/show', [AdminPostController::class, 'show'])->name('admin-post.show');
    Route::get('/admin/post/{post}/edit', [AdminPostController::class, 'edit'])->name('admin-post.edit');
    Route::patch('/admin/post/{post}', [AdminPostController::class, 'update'])->name('admin-post.update');
    Route::patch('/admin/post/update-published/{post}', [AdminPostController::class, 'updatePublished'])->name(
        'admin-post.updatePublished'
    );

    Route::delete('/admin/post/{post}', [AdminPostController::class, 'destroy'])->name('admin-post.destroy');
    Route::get('/admin/posts/search', [AdminPostFilterController::class, 'adminSearch'])->name('admin-post.search');

    Route::post('/admin/tmp-upload', [PhotoController::class, 'store']);
    Route::get('/admin/tmp-restore', [PhotoController::class, 'restore']);
    Route::post('/admin/tmp-reorder', [PhotoController::class, 'reorder']);
    Route::delete('/admin/tmp-revert', [PhotoController::class, 'destroy']);
    Route::post('/admin/post/tmp-upload/{post}', [PhotoPostController::class, 'store']);
    Route::get('/admin/post/tmp-restore/{post}', [PhotoPostController::class, 'restore']);
    Route::post('/admin/post/tmp-reorder/{post}', [PhotoPostController::class, 'reorder']);
    Route::delete('/admin/post/tmp-revert/{post}', [PhotoPostController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/update-catalog-view/{user}', [ProfileController::class, 'updateCatalogView']);
});

require __DIR__ . '/auth.php';
