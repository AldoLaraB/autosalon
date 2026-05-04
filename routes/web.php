<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $recentCars = \App\Models\Car::where('is_active', true)
        ->with('brand')
        ->latest()
        ->limit(6)
        ->get();

    return view('welcome', compact('recentCars'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cerca', [SearchController::class, 'index'])->name('search.index');
Route::get('/search', [SearchController::class, 'search'])->name('search.results');

Route::middleware('auth')->group(function () {
    Route::get('/shops/create', 'App\Http\Controllers\ShopController@create')->name('shops.create');
    Route::post('/shops', 'App\Http\Controllers\ShopController@store')->name('shops.store');
    Route::get('/shops/{shop}/edit', 'App\Http\Controllers\ShopController@edit')->name('shops.edit');
    Route::put('/shops/{shop}', 'App\Http\Controllers\ShopController@update')->name('shops.update');

    Route::get('/cars/create', 'App\Http\Controllers\CarController@create')->name('cars.create');
    Route::post('/cars', 'App\Http\Controllers\CarController@store')->name('cars.store');
    Route::get('/cars/{car}/edit', 'App\Http\Controllers\CarController@edit')->name('cars.edit');
    Route::put('/cars/{car}', 'App\Http\Controllers\CarController@update')->name('cars.update');
    Route::delete('/cars/{car}', 'App\Http\Controllers\CarController@destroy')->name('cars.destroy');

    Route::post('/shops/{shop}/locations', 'App\Http\Controllers\LocationController@store')->name('locations.store');
    Route::delete('/shops/{shop}/locations/{location}', 'App\Http\Controllers\LocationController@destroy')->name('locations.destroy');
});

Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');

// Pubblica: contatta venditore (PRIMA di /cars/{car} per evitare collisioni)
Route::post('/cars/{car}/contact', [ContactController::class, 'store'])
    ->name('cars.contact')
    ->middleware('throttle:3,10');

Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

Route::middleware('auth')->group(function () {
    Route::get('/messages', [ContactController::class, 'index'])->name('messages.index');
    Route::patch('/messages/{contact}/read', [ContactController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{contact}', [ContactController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{contact}/reply', [ContactController::class, 'reply'])->name('messages.reply');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/avatar', [AvatarController::class, 'edit'])->name('profile.avatar');
    Route::put('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [AvatarController::class, 'destroy'])->name('profile.avatar.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', 'App\Http\Controllers\AdminController@usersIndex')->name('admin.users');
    Route::get('/admin/shops', 'App\Http\Controllers\AdminController@shopsIndex')->name('admin.shops');
    Route::get('/admin/cars', 'App\Http\Controllers\AdminController@carsIndex')->name('admin.cars');
    Route::patch('/admin/users/{user}/toggle', 'App\Http\Controllers\AdminController@toggleUserStatus')->name('admin.users.toggle');
    Route::patch('/admin/shops/{shop}/toggle', 'App\Http\Controllers\AdminController@toggleShopStatus')->name('admin.shops.toggle');
    Route::patch('/admin/cars/{car}/toggle', 'App\Http\Controllers\AdminController@toggleCarStatus')->name('admin.cars.toggle');
});

require __DIR__.'/auth.php';
