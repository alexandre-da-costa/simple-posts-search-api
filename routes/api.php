<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('token', 'generateToken');
    Route::delete('token', 'revokeToken')->middleware('auth:sanctum');
});

Route::get('posts', SearchPostController::class)->middleware('check_post_visibility');




