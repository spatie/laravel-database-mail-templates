<?php

namespace Spatie\MailTemplates;

use Illuminate\Support\ServiceProvider;

class MailTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }
}
