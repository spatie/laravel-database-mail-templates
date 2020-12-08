<?php

Route::group([
    'namespace' => 'Akkurate\LaravelAdmin\Http\Controllers\Back',
    'middleware' => config('laravel-admin.routes.back.middleware'),
    'prefix' => config('laravel-admin.routes.back.prefix'),
    'as' => config('laravel-admin.routes.back.as')], function () {
    Route::post('users/{user}/roles/assign', 'UserController@assignRole')->name('users.roles.assign');
    Route::patch('users/{user}/toggle', 'UserController@toggle')->name('users.toggle');
    Route::resource('users', 'UserController');
    Route::patch('accounts/{account}/toggle', 'AccountController@toggle')->name('accounts.toggle');
    Route::resource('accounts', 'AccountController');
    Route::patch('countries/{country}/toggle', 'CountryController@toggle')->name('countries.toggle');
    Route::resource('countries', 'CountryController');
    Route::patch('languages/{language}/toggle', 'LanguageController@toggle')->name('languages.toggle');
    Route::resource('languages', 'LanguageController');
    Route::resource('preferences', 'PreferenceController')->only('update');
});
