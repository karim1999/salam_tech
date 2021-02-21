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

Route::middleware('locale')->namespace('User')->prefix('users')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('sendVerifyCode', 'AuthController@sendVerifyCode');
    Route::post('register', 'AuthController@register');
//    Route::post('forgetPassword', 'AuthController@forgetPassword');
//    Route::post('resetPassword', 'AuthController@resetPassword');
    Route::post('forgetPassword', 'ForgotPasswordController@forgot');
    Route::post('resetPassword', 'ForgotPasswordController@reset');

    Route::get('terms', 'GeneralController@terms');
    Route::get('policy', 'GeneralController@policy');
    Route::get('help', 'GeneralController@help');

    Route::get('cities', 'GeneralController@cities');

    Route::post('labs', 'LabController@index');
    Route::post('labBranches', 'LabController@show');

    Route::post('pharmacies', 'PharmacyController@index');
    Route::post('pharmacyBranches', 'PharmacyController@show');

    Route::get('home', 'HomeController@index');
    Route::post('contactus', 'HomeController@contactUs');

    Route::post('doctors', 'DoctorController@index');
    Route::post('doctorDetails', 'DoctorController@show');

    Route::post('clinics', 'ClinicController@index');
    Route::post('clinicDetails', 'ClinicController@show');

    Route::get('specialists', 'HomeVisitController@specialists');
    Route::post('specialistDoctors', 'HomeVisitController@specialistDoctors');

    Route::middleware('auth:user')->group(function () {
        Route::get('general', 'GeneralController@index');

        Route::post('setProfile', 'SetProfileController@setProfile');
        Route::post('updateProfile', 'SetProfileController@updateProfile');
        Route::post('addAddress', 'SetProfileController@addAddress');

        Route::get('profile', 'ProfileController@index');
        Route::post('updateInfo', 'ProfileController@updateInfo');
        Route::post('updateHealth', 'ProfileController@updateHealth');
        Route::post('editFamily', 'ProfileController@editFamily');
        Route::delete('deleteFamily', 'ProfileController@deleteFamily');

        Route::get('appointments', 'AppointmentController@index');
        Route::post('appointments', 'AppointmentController@show');
        Route::post('bookingAppointment', 'AppointmentController@store');
        Route::put('cancelAppointment', 'AppointmentController@update');

        Route::post('homeVisit', 'HomeVisitController@store');

        Route::get('consultations', 'ConsultationController@index');
        Route::post('messages', 'ConsultationController@show');
        Route::post('sendMessage', 'ConsultationController@store');

        Route::get('favorites', 'FavoriteController@index');
        Route::post('addToFavorites', 'FavoriteController@store');
        Route::delete('removeToFavorites', 'FavoriteController@delete');

        Route::post('rate', 'RateController@store');

        Route::get('EMR', 'EMRController@index');
        Route::get('emrs', 'EMRController@all');
        Route::get('emrs/{id}', 'EMRController@single');
        Route::post('EMRDetails', 'EMRController@show');
    });
});
