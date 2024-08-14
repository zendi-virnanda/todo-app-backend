<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\TodoController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {

    // Auth Routes to Logout and get data of logged in user
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // V1 Routes
    Route::prefix('v1')->group(function () {
        // Todo Routes
        Route::controller(TodoController::class)->prefix('todos')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
            Route::get('/search/find', 'searchTodo');
            Route::post('/{id}/complete', 'complete');
            Route::post('/{id}/incomplete', 'incomplete');
        });
    });
});
