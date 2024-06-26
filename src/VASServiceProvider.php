<?php

namespace EdLugz\VAS;

use Illuminate\Support\ServiceProvider;

class VASServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/vas.php' => config_path('vas.php'),
        ], 'vas.config');

        $this->publishes([
            __DIR__.'/../databases/migrations/' => database_path('migrations'),
        ], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/vas.php', 'vas');

        // Register the service the package provides.
        $this->app->singleton('vas', function ($app) {
            return new VAS();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['vas'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/vas.php' => config_path('vas.php'),
        ], 'vas.config');
    }
}
