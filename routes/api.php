<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('user')->controller(\App\Http\Controllers\UserController::class)->name('user.')
    ->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::middleware('auth:api')->group(function () {
            Route::post('/refresh', 'refresh')->name('refresh');
            Route::get('/details', 'details')->name('details');
            Route::get('/exchange/{from}/{to}', 'exchangeCurrency')->name('exchange');
            Route::post('/logout', 'logout')->name('logout');
        });
    });

