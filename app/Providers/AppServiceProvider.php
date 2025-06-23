<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade as PDF;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (class_exists('Barryvdh\\DomPDF\\ServiceProvider')) {
            $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        }
        if (class_exists('Barryvdh\\DomPDF\\Facade')) {
            $this->app->alias('PDF', \Barryvdh\DomPDF\Facade::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
