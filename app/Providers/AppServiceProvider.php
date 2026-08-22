<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();

        if (!app()->runningInConsole()) {
            $systemSettings = \App\Models\SystemSetting::getSingle();
            view()->share('systemSettings', $systemSettings);

            $homeSetting = \App\Models\HomeSetting::getSingle();
            view()->share('homeSetting', $homeSetting);
        }
    }
}
