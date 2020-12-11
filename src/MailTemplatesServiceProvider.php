<?php

namespace Spatie\MailTemplates;

use Illuminate\Support\ServiceProvider;

class MailTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('laravel-mail-templates.routes.back.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
        if (config('laravel-mail-templates.routes.api.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-mail-templates.php',
            'laravel-mail-templates'
        );
    }
}
