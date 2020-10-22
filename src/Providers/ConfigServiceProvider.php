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
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('laravelconfig.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ggphp-config');
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
        $this->composeView();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }

    protected function composeView()
    {
        view()->composer(['ggphp-config::config'], function ($view) {
            $fields = config('laravelconfig.fields');
            $configs = app('GGPHP\Config\Helpers\Config');

            $view->with(['fields' => $fields, 'configs' => $configs]);
        });
    }
}
