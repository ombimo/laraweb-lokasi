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
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        //migration
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        //models
        $this->publishes([
            __DIR__ . '/../resources/models' => app_path('Models\Lokasi'),
        ], 'models');

        //config
        $this->publishes([
            __DIR__ . '/../config/laraweb-lokasi.php' => config_path('laraweb-lokasi.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Seeder::class,
                Commands\SeederNegara::class,
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
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laraweb-lokasi.php', 'laraweb-lokasi'
        );
    }
}
