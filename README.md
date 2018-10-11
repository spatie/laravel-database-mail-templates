# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mail-templates)
[![Build Status](https://img.shields.io/travis/spatie/laravel-mail-templates/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-mail-templates)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-mail-templates.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-mail-templates)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mail-templates)

Render Laravel mailables using a template stored in the database. Name is a wip.

## Notes

- MailTemplate moet aanpasbaar model zijn
    - zoals permission package default voorzien maar users aanraden om eigen model te maken?
    - meerdere models moeten mogelijk zijn
        - bv. `MailTemplate` voor algemene mails en `EventMailTemplate` voor template gekoppeld aan event
- Variabelen per email type template instellen
    - in template string en in subject
    - moustache `{{ var }}` syntax
    - variabelen kunnen ophalen om bij wysiwyg editor weer te geven 
        - static method in mailable? 
        - kan niet uit view data komen want geen instance
        - misschien aanraden om alles in public properties te steken want toch geen build nodig?
- Markdown templates?
    - why not - bijhouden in db wel
- Mail template body localized?
    - json column? of in userland?
- Layouts?
    - Manier om rond de template nog een header en footer toe te voegen
    - Mogelijkheid tot blade view, markdown, html (uit database) of gewoon geen
    - Layout is gekoppeld aan MailTemplate (model) of TemplateMailable (class)?
- Betere naam nodig
- Nova tool?

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-mail-templates
```

## Usage

``` php
$skeleton = new Spatie\MailTemplates();
echo $skeleton->echoPhrase('Hello, Spatie!');
```

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
