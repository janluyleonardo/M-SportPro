<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Blade::if('module', function ($slug) {
            if (!auth()->check()) return false;
            if (auth()->user()->is_super_admin) return true;
            if (!auth()->user()->club) return false;
            return auth()->user()->club->hasModule($slug);
        });

        \Illuminate\Support\Facades\Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->is_super_admin;
        });
    }
}
