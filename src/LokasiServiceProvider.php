<?php

namespace Ombimo\LarawebLokasi;

use Illuminate\Support\ServiceProvider;

class LokasiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        //route
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        //migration
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Seeder::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
