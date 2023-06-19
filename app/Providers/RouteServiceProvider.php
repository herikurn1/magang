<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapBudgetRoutes();

        $this->mapOrganisasiRoutes();

        // add Fahmi 11 Mei 2020
        $this->mapSqiiRoutes();

        // add Dimas 27 Juni 2021
        $this->mapDwRoutes();

        // add Septiyan 21 Jan 2022
        $this->mapVouchermallRoutes();    
            
        // add Dimas 14 Maret 2022
        $this->mapPpRoutes();

        // add Galang 30 Juni 2022
        $this->mapCmstrhubRoutes(); 

        $this->mapCostingRoutes();

        $this->mapLegallandRoutes();

        $this->mapApikRoutes();
		
        // add Waris 11 Oktober 2022
        $this->mapBujpRoutes();

         // add naura 2023
         $this->mapSmmsRoutes();
		
		//add irfan 31/10/22
		$this->mapSecLogBookRoutes();

        // add Aji 03/04/23
        $this->mapConfirmationAttendanceRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "budgetRoutes" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapBudgetRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/budgetRoutes.php'));
    }

    protected function mapOrganisasiRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/organisasiRoutes.php'));
    }

    // add Septiyan 21 Jan 2022
    protected function mapVouchermallRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/vouchermallRoutes.php'));
    }

    // add Fahmi 11 mei 2020
    protected function mapSqiiRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/sqiiRoutes.php'));
    }

    // add Dimas 27 Jun 2021 untuk aplikasi Downtown
    protected function mapDwRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/dwRoutes.php'));
    }

    // add Dimas 14 Maret 2022 untuk aplikasi PPRS
    protected function mapPpRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/ppRoutes.php'));
    }

    // add Galang 30 Juni 2022
    protected function mapCmstrhubRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/cmstrhubRoutes.php'));
    }

    protected function mapLegallandRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/legallandRoutes.php'));
    }

    protected function mapCostingRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/costingRoutes.php'));
    }

    // add APIK by Riadi
    protected function mapApikRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/apikRoutes.php'));
    }

    // add Waris 11 Oktober 2022
    protected function mapBujpRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/bujpRoutes.php'));
    }

    // add naura 2023
    protected function mapSmmsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/smmsRoutes.php'));
    }

	//add irfan 31/10/22
	protected function mapSecLogBookRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/secLogBookRoutes.php'));
    }

    //add Aji 03/04/23 Undangan & Rencana Kehadiran
	protected function mapConfirmationAttendanceRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/confirmationAttendanceRoutes.php'));
    }
	
    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
