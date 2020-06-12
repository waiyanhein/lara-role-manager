<?php

namespace Waiyanhein\LaraRoleManager;

use Illuminate\Support\ServiceProvider;

class LaraRoleManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/roles.php' => config_path('roles.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/factories');
    }
}
