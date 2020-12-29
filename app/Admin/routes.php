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
    $router->resource('doctors', DoctorController::class);
    $router->resource('posts', PostController::class);

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
