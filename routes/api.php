<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostsController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware(['auth:api', 'jwt_auth']);
    Route::post('refresh', 'refresh')->middleware(['auth:api', 'jwt_auth']);
    Route::get('profil', 'profil')->middleware(['auth:api', 'jwt_auth']);
});

Route::prefix('user')->middleware(['auth:api', 'jwt_auth', 'check.role'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/{id}/show', [UserController::class, 'show']);
    Route::put('/{id}/update', [UserController::class, 'update']);
    Route::put('/{id}/destroy', [UserController::class, 'destroy']);
});

Route::prefix('post')->middleware(['auth:api', 'jwt_auth', 'aksess.user'])->group(function () {
    Route::get('/', [PostsController::class, 'index']);
});
