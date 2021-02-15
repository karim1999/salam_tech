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

Route::middleware('locale')->namespace('Doctor')->prefix('doctors')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('sendVerifyCode', 'AuthController@sendVerifyCode');
    Route::post('register', 'AuthController@register');
    Route::post('forgetPassword', 'AuthController@forgetPassword');
    Route::post('resetPassword', 'AuthController@resetPassword');

    Route::get('terms', 'GeneralController@terms');
    Route::get('policy', 'GeneralController@policy');
    Route::get('help', 'GeneralController@help');

    Route::get('cities', 'GeneralController@cities');
    Route::get('specialists', 'GeneralController@specialists');

    Route::middleware('auth:doctor')->group(function () {
        Route::get('general', 'GeneralController@index');

        Route::post('setProfile', 'SetProfileController@setProfile');

        Route::get('vacations', 'VacationController@index');
        Route::post('vacations', 'VacationController@store');

        Route::get('appointments', 'AppointmentController@index');
        Route::post('appointments', 'AppointmentController@show');
        Route::post('cancelAppointments', 'AppointmentController@store');

        Route::post('EMR', 'EMRController@index');

        Route::post('createEMR', 'EMRController@create');
        Route::post('updateEMR', 'EMRController@store');
        Route::delete('deleteEMRDocument', 'EMRController@deleteDocument');

        Route::get('consultations', 'ConsultationController@index');
        Route::post('messages', 'ConsultationController@show');
        Route::post('sendMessage', 'ConsultationController@store');

        Route::get('profile', 'ProfileController@index');
        Route::post('updateInfo', 'ProfileController@updateInfo');
        Route::post('updateWork', 'ProfileController@updateWork');
        Route::delete('deleteProfileDocument', 'ProfileController@deleteDocument');

        Route::post('rate', 'RateController@store');
    });
});
