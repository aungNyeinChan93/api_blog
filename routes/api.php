<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->middleware(['auth:sanctum']);
Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);


Route::middleware(['auth:sanctum'])->group(function () {

    // Profile
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('profile', [ProfileController::class, 'update']);
    Route::post('profile', [ProfileController::class, 'password_change']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'auth:sanctum']], function () {

    //Categories
    Route::apiResource('categories', CategoryController::class);

});

