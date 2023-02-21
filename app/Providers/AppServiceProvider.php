<?php

namespace App\Providers;

use App\Helpers\ArrayHelper;
use App\Helpers\PeriodHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ArrayHelper::class, function($app) {
            return new ArrayHelper();
        });

        $this->app->singleton(PeriodHelper::class, function($app) {
            return new PeriodHelper();
        });

        $this->app->singleton(LocationHelper::class, function($app) {
            return new LocationHelper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
