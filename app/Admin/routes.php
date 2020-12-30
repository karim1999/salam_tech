<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('contacts', ContactMessageController::class);

    $router->group([
        'prefix'        => 'settings',
        'as'            => 'settings.',
    ], function (Router $router) {
        $router->resource('settings', SettingController::class);
        $router->resource('cities', CityController::class);
        $router->resource('areas', AreaController::class);
    });
    $router->group([
        'prefix'        => 'labs',
        'as'            => 'labs.',
    ], function (Router $router) {
        $router->resource('labs', LabController::class);
        $router->resource('branches', LabBrancheController::class);
    });
    $router->group([
        'prefix'        => 'pharmacies',
        'as'            => 'pharmacies.',
    ], function (Router $router) {
        $router->resource('pharmacies', PharmacyController::class);
        $router->resource('branches', PharmacyBrancheController::class);
    });

    $router->group([
        'prefix'        => 'doctors',
        'as'            => 'doctors.',
    ], function (Router $router) {
        $router->resource('doctors', DoctorController::class);
        $router->resource('emrs', EmrController::class);
        $router->resource('appointments', AppointmentController::class);
        $router->resource('consultations', ConsultationController::class);
    });

    $router->group([
        'prefix'        => 'patients',
        'as'            => 'patients.',
    ], function (Router $router) {
        $router->resource('patients', UserController::class);
        $router->resource('favorites', FavoriteController::class);
        $router->resource('families', UserFamilyController::class);
        $router->resource('rates', RateController::class);
    });

    $router->group([
        'prefix'        => 'clinics',
        'as'            => 'clinics.',
    ], function (Router $router) {
        $router->resource('clinics', ClinicController::class);
        $router->resource('branches', ClinicBrancheController::class);
        $router->resource('documents', ClinicDocumentController::class);
        $router->resource('images', ClinicImageController::class);
        $router->resource('products', ClinicProductController::class);
        $router->group([
            'prefix'        => 'employees',
            'as'            => 'employees.',
        ], function (Router $router) {
            $router->resource('employees', ClinicEmployeeController::class);
            $router->resource('attendances', EmployeeAttendanceController::class);
        });
        $router->resource('specialists', SpecialistController::class);
    });

    $router->group([
        'prefix'        => 'api',
        'as'            => 'api.',
    ], function (Router $router) {

        $router->get('areas', 'APIController@areas');
        $router->get('cities', 'APIController@cities');
    });

});
