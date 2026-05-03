<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route pubbliche
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route protette
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return UserResource::make($request->user()->load(['roles', 'permissions', 'media']));
    });

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // API Negozi
    Route::get('/shops', [ShopApiController::class, 'index']);
    Route::get('/shops/{shop}', [ShopApiController::class, 'show']);
    Route::post('/shop/request', [ShopApiController::class, 'requestDealer']);

    // API Auto
    Route::get('/cars', [CarApiController::class, 'index']);
    Route::get('/cars/{car}', [CarApiController::class, 'show']);
    Route::post('/cars', [CarApiController::class, 'store']);
    Route::put('/cars/{car}', [CarApiController::class, 'update']);
});
