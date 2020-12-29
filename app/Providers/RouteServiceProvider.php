<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapUserRoutes();

        $this->mapDoctorRoutes();

        $this->mapClinicRoutes();

        $this->mapAdminRoutes();
    }

    /**
     * Define the "api users" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapUserRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/user.php'));
    }

    /**
     * Define the "api doctors" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapDoctorRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/doctor.php'));
    }

    /**
     * Define the "api clinics" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapClinicRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/clinic.php'));
    }

    /**
     * Define the "api admins" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
}
