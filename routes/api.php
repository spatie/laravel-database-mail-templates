<?php

Route::group([
    'namespace' => 'Spatie\MailTemplates\Http\Controllers\Api',
    'middleware' => config('laravel-mail-templates.routes.api.middleware'),
    'prefix' => config('laravel-mail-templates.routes.api.prefix'),
    'as' => config('laravel-mail-templates.routes.api.as')], function () {

    Route::apiResource('templates', 'MailTemplateController')->only(['index', 'show']);
});
