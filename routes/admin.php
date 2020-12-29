<?php

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

Route::middleware('locale')->namespace('Admin')->prefix('admins')->group(function () {
    Route::post('login', 'AuthController@login');

    Route::middleware('auth:admin')->group(function (){
        Route::resource('roles', 'RoleController');
        Route::resource('admins', 'AdminController');
        Route::resource('cities', 'CityController');
        Route::resource('areas', 'AreaController');
        Route::resource('specialists', 'SpecialistController');
    });
});
