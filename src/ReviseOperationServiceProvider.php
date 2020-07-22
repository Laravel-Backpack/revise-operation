<?php

namespace Backpack\ReviseOperation;

use Illuminate\Support\ServiceProvider;

class ReviseOperationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'revise-operation');

        // load views
        // - from 'resources/views/vendor/backpack/revise-operation' if they're there
        // - otherwise fall back to package views
        $this->loadViewsFrom(resource_path('views/vendor/backpack/revise-operation'), 'revise-operation');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'revise-operation');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/backpack'),
        ], 'revise-operation.views');

        // Publishing the translation files.
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/backpack'),
        ], 'revise-operation.lang');
    }
}
