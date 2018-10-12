# Render Laravel mailables based on a mail template stored in the database

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-database-mail-templates)
[![Build Status](https://img.shields.io/travis/spatie/laravel-database-mail-templates/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-database-mail-templates)
[![StyleCI](https://github.styleci.io/repos/152581258/shield?branch=master)](https://github.styleci.io/repos/152581258)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-database-mail-templates)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-database-mail-templates)

Render Laravel mailables using a template stored in the database.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-database-mail-templates
```

If you want to use the [default `MailTemplate` model](#default-mailtemplate-model), all that's left to do is run `php artisan migrate` to create the necessary `mail_templates` table. No need to publish the migrations. 

If you plan on creating a [custom `MailTemplate` model](#custom-mailtemplate-model) continue by publishing the migrations to further modify:

```bash
php artisan vendor:publish --provider="Spatie\MailTemplates\MailTemplatesServiceProvider" --tag="migrations"
```

## Usage

By default the package comes with a `MailTemplate` model that allows you to store the HTML template for a mailable in the database. 
If you don't need to multiple templates for a single mailable, the [default `MailTemplate` model](#default-mailtemplate-model) is way to go. 
However, if you want to use different mail templates per mailable based on related models in your application, you'll want a [custom `MailTemplate` model](#custom-mailtemplate-model).

### Default `MailTemplate` model

After installing the package and running `php artisan migrate` you'll have a new table in your database called `mail_templates`.

...

### Custom `MailTemplate` model

...

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Alex Vanderbist](https://github.com/alexvanderbist)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
