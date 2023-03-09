<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear', function() {    
    Artisan::call('cache:clear');    
    Artisan::call('config:cache');    
    Artisan::call('view:clear');  
    Artisan::call('route:clear');   
    Cache::store('redis')->flush();
    return response()->json([
        'message' => 'Кэш успешно очищен!'
    ], 200);
})->name('clear');

Route::group([
    'controller' => AuthController::class,
], function() {
    Route::post('login', 'login');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('profile', 'profile');
        Route::get('logout', 'logout');
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::group([
        'controller' => SettingsController::class
    ], function() {
        Route::get('/settings', 'index');
        Route::post('/settings', 'save');
    });

    Route::group([
        'controller' => Admin\RoleController::class
    ], function() {
        Route::get('/roles', 'index');
    });
    
    Route::group([
        'controller' => StatisticController::class,
        'prefix' => 'statistics'
    ], function() {
        Route::get('/breaking', 'breaking');
        Route::get('/plunk', 'plunk');
        Route::get('/auth', 'auth');
        Route::get('/statistics', 'index');
        Route::get('/reports', 'reports');
        Route::get('/item', 'item');
        Route::get('/group-item', 'groupItem');
        Route::get('/create-array', 'replication');
    });
    Route::apiResources([
        'verified' => VerifiedController::class,
        'suspicious' => SuspiciousController::class,
        'users' => Admin\UserController::class
    ]);
});