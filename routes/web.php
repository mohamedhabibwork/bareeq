<?php

use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');
Route::redirect('/home', 'dashboard');

Route::controller('\Barryvdh\TranslationManager\Controller')->prefix(config('translation-manager.route.prefix','translations'))->group( function($router) {
    $router->get('view/{groupKey?}', 'getView')->where('groupKey', '.*');
    $router->get('/{groupKey?}', 'getIndex')->where('groupKey', '.*');
    $router->post('/add/{groupKey}', 'postAdd')->where('groupKey', '.*');
    $router->post('/edit/{groupKey}', 'postEdit')->where('groupKey', '.*');
    $router->post('/groups/add', 'postAddGroup');
    $router->post('/delete/{groupKey}/{translationKey}', 'postDelete')->where('groupKey', '.*');
    $router->post('/import', 'postImport');
    $router->post('/find', 'postFind');
    $router->post('/locales/add', 'postAddLocale');
    $router->post('/locales/remove', 'postRemoveLocale');
    $router->post('/publish/{groupKey}', 'postPublish')->where('groupKey', '.*');
    $router->post('/translate-missing', 'postTranslateMissing');
});
