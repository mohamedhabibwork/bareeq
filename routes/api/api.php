<?php

use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkerController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'users'], function () {
    Route::post('/login', [UserController::class, 'login'])->middleware(['guest:users']);
    Route::post('/register', [UserController::class, 'store'])->middleware(['guest:users']);
    Route::group(['middleware' => 'auth:users',], function () {
        Route::get('best/plans', [PlanController::class, 'index']);
        Route::get('plans', [PlanController::class, 'index']);
        Route::post('/car', [UserController::class, 'attachCar']);
        Route::post('subscribe/plan', [UserController::class, 'subscribe']);
        Route::get('user', [UserController::class, 'show']);
        Route::get('notifications', [UserController::class, 'notifications']);
        Route::post('user/update', [UserController::class, 'update']);
        Route::get('orders', [UserController::class, 'orders']);
        Route::post('orders', [UserController::class, 'storeOrder']);
        Route::post('orders/{id}/update/status', [UserController::class, 'statusOrder']);
        Route::get('requests', [UserController::class, 'singles']);
        Route::post('requests', [UserController::class, 'createSingleRequest']);
    });
});
