<?php

use App\Http\Controllers\Lot\AdminLotController;
use App\Http\Controllers\Lot\LotController;
use App\Http\Controllers\Lot\PhotoController;
use App\Http\Controllers\Lot\PhotoLotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\AdminUserController;
use App\Http\Controllers\User\BetController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main/Home');
})->name('home');

Route::get('/contact', function () {
    return Inertia::render('Main/Contact');
})->name('contact');

Route::middleware(['auth', 'verified', 'roles:trusted,admin'])->group(function () {
    Route::get('/main/bets/index', [BetController::class, 'index'])->name('main-bets.index');
    Route::post('/main/bets/store/{lot}', [BetController::class, 'store'])->name('main-bets.store');


    Route::patch('/main/bets/test', [BetController::class, 'test'])->name('main-bets.test');
});

Route::middleware(['auth', 'verified', 'roles:admin'])->group(function () {
    Route::get('/admin/lots/index', [AdminLotController::class, 'index'])->name('admin-lots.index');
    Route::get('/admin/lots/index/filter', [AdminLotController::class, 'index'])
        ->name('admin-lots.filter');
    Route::get('/admin/lots/search', [AdminLotController::class, 'index'])->name('admin-lots.search');

    Route::get('/admin/lot/create', [AdminLotController::class, 'create'])->name('admin-lot.create');
    Route::post('/admin/lot/create', [AdminLotController::class, 'store'])->name('admin-lot.store');
    Route::get('/admin/lot/{lot}/show', [AdminLotController::class, 'show'])->name('admin-lot.show');
    Route::get('/admin/lot/{lot}/edit', [AdminLotController::class, 'edit'])->name('admin-lot.edit');
    Route::patch('/admin/lot/{lot}', [AdminLotController::class, 'update'])->name('admin-lot.update');
    Route::patch('/admin/lot/update-published/{lot}', [AdminLotController::class, 'updatePublished'])->name(
        'admin-lot.updatePublished'
    );
    Route::delete('/admin/lot/{lot}', [AdminLotController::class, 'destroy'])->name('admin-lot.destroy');

    Route::post('/admin/lot/crete/brand', [AdminLotController::class, 'storeBrand'])->name('admin-lot.storeBrand');
    Route::post('/admin/lot/crete/model', [AdminLotController::class, 'storeModel'])->name('admin-lot.storeModel');

    Route::post('/admin/lot/create/tmp-upload', [PhotoController::class, 'store']);
    Route::get('/admin/lot/create/tmp-restore', [PhotoController::class, 'restore']);
    Route::post('/admin/lot/create/tmp-reorder', [PhotoController::class, 'reorder']);
    Route::delete('/admin/lot/create/tmp-revert', [PhotoController::class, 'destroy']);
    Route::post('/admin/lot/tmp-upload/{lot}', [PhotoLotController::class, 'store']);
    Route::get('/admin/lot/tmp-restore/{lot}', [PhotoLotController::class, 'restore']);
    Route::post('/admin/lot/tmp-reorder/{lot}', [PhotoLotController::class, 'reorder']);
    Route::delete('/admin/lot/tmp-revert/{lot}', [PhotoLotController::class, 'destroy']);

    Route::get('/admin/users/index', [AdminUserController::class, 'index'])->name('admin-users.index');
    Route::get('/admin/user/{user}/show', [AdminUserController::class, 'show'])->name('admin-user.show');
    Route::get('/admin/user/{user}/edit', [AdminUserController::class, 'edit'])->name('admin-user.edit');
    Route::patch('/admin/update-role/{user}', [AdminUserController::class, 'updateRole']);
});

Route::get('/main/lots/index', [LotController::class, 'index'])->name('main-lots.index');
Route::get('/main/lots/index/filter', [LotController::class, 'index'])
    ->name('main-lots.filter');

Route::get('/main/lots/search', [LotController::class, 'index'])->name('main-lots.search');
Route::get('/main/lot/{lot}/show', [LotController::class, 'show'])->name('main-lot.show');

Route::post('/get-model', [LotController::class, 'getModel'])->name('getModel');
Route::patch('/lots/filter/update-catalog-view', [ProfileController::class, 'updateCatalogView'])->name(
    'lot-filter.updateCatalogView'
);
Route::post('/lots/filter/get-count', [LotController::class, 'filterCount'])->name('lot-filter.filterCount');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
