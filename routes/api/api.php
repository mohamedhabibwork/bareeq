<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkerController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'users'], function () {
    Route::post('/login', [UserController::class, 'login'])->middleware(['guest:users']);
    Route::post('/register', [UserController::class, 'store'])->middleware(['guest:users'])->middleware('throttle:2');
    Route::get('/cities', [CityController::class, 'index']);
    Route::group(['middleware' => 'auth:users',], function () {
        Route::get('best/plans', [PlanController::class, 'index']);
        Route::get('plans', [PlanController::class, 'index']);
        Route::post('/checkArea', [CityController::class, 'checkArea']);
        Route::post('/car', [UserController::class, 'attachCar']);
        Route::post('subscribe/plan', [UserController::class, 'subscribe']);
        Route::get('user', [UserController::class, 'show']);
        Route::get('notifications', [UserController::class, 'notifications']);
        Route::post('user/update', [UserController::class, 'update']);
        Route::post('change/password', [UserController::class, 'changePassword']);
        Route::get('orders', [UserController::class, 'orders']);
        Route::post('orders', [UserController::class, 'storeOrder']);
        Route::post('rate/orders/{id}', [UserController::class, 'rateOrder']);
        Route::post('orders/{id}/update/status', [UserController::class, 'statusOrder']);
        Route::get('requests', [UserController::class, 'singles']);
        Route::post('requests', [UserController::class, 'createSingleRequest']);

        Route::post('otp/check/code', [UserController::class, 'checkOTP'])->middleware('throttle:2')->withoutMiddleware('auth:users');
        Route::post('otp/code', [UserController::class, 'generateOTPCode'])->middleware('throttle:2');
        Route::post('otp/verify', [UserController::class, 'verifyOTPCode'])->middleware('throttle:2');

        Route::post('reset/code', [UserController::class, 'generateOTPCode'])->middleware('throttle:2')->withoutMiddleware('auth:users');
        Route::post('reset/password', [UserController::class, 'resetPassword'])->middleware('throttle:2')->withoutMiddleware('auth:users');

    });
});
