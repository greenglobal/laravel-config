<?php

namespace GGPHP\Config\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        include __DIR__ . '/../Helpers/Config.php';

        if (env('STORE_DB', 'database') == 'database')
            $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ggphp-config');
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../config/system.php', 'config.system'
        );
    }
}
