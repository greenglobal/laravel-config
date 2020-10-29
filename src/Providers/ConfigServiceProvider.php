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

        $this->publishes([
            __DIR__ . '/../../config/system.php' => config_path('ggconfig.php'),
        ], 'config');

        if (env('STORE_DB', 'database') == 'database')
            $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ggphp-config');
        $this->loadRoutesFrom(__DIR__. ' /../Http/routes.php');
        $this->composeView();
    }

    protected function composeView()
    {
        view()->composer(['ggphp-config::config'], function ($view) {
            $fields = config('ggconfig.fields');

            $view->with(['fields' => $fields]);
        });
    }
}
