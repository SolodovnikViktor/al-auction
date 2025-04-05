<?php

use App\Http\Controllers\Post\AdminPostController;
use App\Http\Controllers\Post\ImageController;
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
    Route::get('/admin/posts', [AdminPostController::class, 'index'])->name('admin-post.index');
    Route::get('/admin/post/create', [AdminPostController::class, 'create'])->name('admin-post.create');
    Route::post('/admin/post/create', [AdminPostController::class, 'store'])->name('admin-post.store');
    Route::get('/admin/post/{post}/show', [AdminPostController::class, 'show'])->name('admin-post.show');
    Route::get('/admin/post/{post}/edit', [AdminPostController::class, 'edit'])->name('admin-post.edit');
    Route::patch('/admin/post/{post}', [AdminPostController::class, 'update'])->name('admin-post.update');
    Route::patch('/admin/post/update-published/{post}', [AdminPostController::class, 'updatePublished'])->name(
        'admin-post.updatePublished'
    );

    Route::delete('/admin/post/{post}', [AdminPostController::class, 'destroy'])->name('admin-post.destroy');

    Route::post('/admin/tmp-upload', [ImageController::class, 'store']);
    Route::get('/admin/tmp-restore', [ImageController::class, 'restore']);
    Route::post('/admin/tmp-reorder', [ImageController::class, 'reorder']);
    Route::delete('/admin/tmp-revert', [ImageController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
