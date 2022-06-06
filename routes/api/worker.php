<?php

use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\WorkerUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:workers')->get('/worker', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'workers'], function () {
    Route::post('/login', [WorkerController::class, 'login']);

    Route::group(['middleware' => 'auth:workers',], function () {
        Route::get('orders', [WorkerController::class, 'orders']);
        Route::post('orders/{id}/started/order', [WorkerUserController::class, 'startOrder']);
        Route::post('orders/{id}', [WorkerController::class, 'finishOrder']);
        Route::get('worker', [WorkerController::class, 'show']);
        Route::post('worker/update', [WorkerController::class, 'update']);
        Route::post('change/password', [WorkerController::class, 'changePassword']);
    });
});
