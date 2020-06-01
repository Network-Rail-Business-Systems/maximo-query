<?php

namespace Nrbusinesssystems\MaximoQuery\Providers;

use Illuminate\Support\ServiceProvider;
use Nrbusinesssystems\MaximoQuery\MaximoQuery;

class MaximoQueryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('maximo-query.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'maximo-query');

        // Register the main class to use with the facade
        $this->app->singleton('maximo-query', function () {
            return new MaximoQuery();
        });
    }
}
