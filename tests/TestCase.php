<?php

namespace GGPHP\Config\Tests;

use GGPHP\Config\Providers\ConfigServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Kreait\Laravel\Firebase\ServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->artisan('vendor:publish', ['--provider' => 'GGPHP\Config\ConfigServiceProvider']);
        $this->artisan('vendor:publish', [
          '--provider' => 'Kreait\Laravel\Firebase\ServiceProvider',
          '--tag' => 'config']);
        $this->artisan('migrate', ['--database' => 'testing']);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * @environment-setup useMySqlConnection
     */
    protected function useMySqlConnection($app)
    {
        $app->config->set('database.default', 'mysql');
    }

    /**
     * Register services.
     */
    protected function getPackageProviders($app)
    {
        return [
            ConfigServiceProvider::class,
            ServiceProvider::class
        ];
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'GGPHP\Config\Http\Kernel');
    }
}
