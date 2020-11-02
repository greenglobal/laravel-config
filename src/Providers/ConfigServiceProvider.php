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
        $this->composeView();
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

    /**
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer(['ggphp-config::config'], function ($view) {
            $configs = config('config.system');
            $data = array_filter($configs, function($value) {
                if (! empty($value['key'])) {
                    return $value['key'] == 'configuration.system.fields';
                }
            });

            $view->with(['data' => array_pop($data)]);
        });
    }
}
