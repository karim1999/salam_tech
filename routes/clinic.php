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

Route::middleware('locale')->namespace('Clinic')->prefix('clinics')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('sendVerifyCode', 'AuthController@sendVerifyCode');
    Route::post('register', 'AuthController@register');
    Route::post('forgetPassword', 'AuthController@forgetPassword');
    Route::post('resetPassword', 'AuthController@resetPassword');

    Route::get('terms', 'GeneralController@terms');
    Route::post('cities', 'GeneralController@cities');

    Route::middleware('auth:clinic')->group(function () {
        Route::post('setProfile', 'SetProfileController@setProfile');

        Route::get('appointments', 'AppointmentController@index');
        Route::post('appointments', 'AppointmentController@store');
        Route::post('deleteAppointment', 'AppointmentController@destroy');

        Route::get('doctors', 'DoctorController@index');
        Route::post('showDoctor', 'DoctorController@show');
        Route::post('doctors', 'DoctorController@store');
        Route::post('deleteDoctor', 'DoctorController@destroy');

        Route::get('employees', 'EmployeeController@index');
        Route::post('employees', 'EmployeeController@store');
        Route::post('updateEmployee', 'EmployeeController@update');
        Route::post('deleteDocument', 'EmployeeController@deleteDoc');
        Route::post('deleteEmployee', 'EmployeeController@destroy');

        Route::get('attendance', 'AttendanceController@index');
        Route::post('attendance', 'AttendanceController@store');

        Route::get('products', 'ProductController@index');
        Route::post('products', 'ProductController@store');
        Route::post('updateProduct', 'ProductController@update');
        Route::post('deleteProduct', 'ProductController@destroy');
        Route::post('deposit', 'ProductController@deposit');
        Route::post('withdraw', 'ProductController@withdraw');
    });
});
