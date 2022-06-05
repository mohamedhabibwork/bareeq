<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
$callback = function () {
    Route::get('/', 'index')->name('index');
    Route::get('/deleted', 'index')->name('deleted')->withTrashed();
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/datatable', 'datatable')->name('datatable');
    Route::get('/{id}', 'show')->name('show');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::post('/status/{id}', 'status')->name('status');
    Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
    Route::post('/restore/{id}', 'restore')->name('restore');
    Route::delete('/forceDelete/{id}', 'forceDelete')->name('forceDelete');
    Route::delete('/{id}', 'destroy')->name('destroy');
};

Route::group(['middleware' => 'auth:web'],function () use ($callback) {

    Route::controller('UserController')
        ->prefix('users')
        ->name('users.')->group($callback);

    Route::controller('CityController')
        ->prefix('cities')
        ->name('cities.')->group($callback);

    Route::controller('AdminController')
        ->prefix('admins')
        ->name('admins.')->group($callback);

    Route::controller('CarController')
        ->prefix('cars')
        ->name('cars.')->group($callback);

    Route::controller('WorkerController')
        ->prefix('workers')
        ->name('workers.')->group($callback);

    Route::controller('PlanController')
        ->prefix('plans')
        ->name('plans.')->group($callback);

    Route::controller('UserPlanController')
        ->prefix('user-plans')
        ->name('userPlan.')->group($callback);

    Route::controller('WorkerUserController')
        ->prefix('worker-users')
        ->name('worker_users.')->group($callback);


    Route::controller('SingleRequestController')
        ->prefix('single-request')
        ->name('singleRequest.')->group($callback);

    Route::controller('SingleRequestController')
        ->prefix('single-request')
        ->name('singleRequest.')->group(function () {
            Route::post('/accept/{id}','accept')->name('accept');
        });

    Route::get('/', [HomeController::class, 'index'])->name('home');

});
Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false,
]);

