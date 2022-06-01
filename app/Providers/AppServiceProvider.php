<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        $this->loadViewsFrom(resource_path('views/dashboard'), 'dashboard');
        Blade::anonymousComponentNamespace(resource_path('views/dashboard/layout'), 'layout');
        require_once __DIR__ . '/RepositoryBinding.php';
    }
}
