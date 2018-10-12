# Render Laravel mailables based on a mail template stored in the database

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-database-mail-templates)
[![Build Status](https://img.shields.io/travis/spatie/laravel-database-mail-templates/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-database-mail-templates)
[![StyleCI](https://github.styleci.io/repos/152581258/shield?branch=master)](https://github.styleci.io/repos/152581258)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-database-mail-templates)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-database-mail-templates)

Render Laravel mailables using a template stored in the database.

## Quick example

The following example will send a `WelcomeMail` using a template stored in the database and wrapped in an HTML layout.

```php
class WelcomeMail extends \Spatie\MailTemplates\TemplateMailable
{
    /** @var string */
    public $name;

    public function __construct(User $user)
    {
        $this->name = $user->name;
    }
    
    public function getLayout(): string
    {
        return file_get_contents(storage_path('mail-layouts/main.html'));
    }
}

MailTemplate::create([
    'mailable' => WelcomeMail::class,
    'template' => '<p>Hello, {{ name }}.</p>',
]);

Mail::to($user->email)->send(new WelcomeMail($user));
```

The HTML for the sent email will look like this:

```html
<header>Welcome!</header>
<p>Hello, John.</p>
<footer>Copyright 2018</footer>
```

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-database-mail-templates
```

If you want to use the [default `MailTemplate` model](#default-mailtemplate-model), all that's left to do is run `php artisan migrate` to create the necessary `mail_templates` table. No need to publish the migrations. 

If you plan on creating a [custom `MailTemplate` model](#custom-mailtemplate-model) continue by publishing the migrations:

```bash
php artisan vendor:publish --provider="Spatie\MailTemplates\MailTemplatesServiceProvider" --tag="migrations"
```

## Usage

### Default or custom `MailTemplate`?

By default this package comes with a `MailTemplate` model that allows you to store the HTML template for a mailable in the database. 
If you don't need to multiple templates for a single mailable, the [default `MailTemplate` model](#default-mailtemplate-model) is way to go. 
However, if you want to use different mail templates for the same mailable or associate mail templates with models in your application, you'll want a [custom `MailTemplate` model](#custom-mailtemplate-model).

### Default `MailTemplate` model

After installing the package and running `php artisan migrate` you'll have a new table in your database called `mail_templates` that'll be used by the `MailTemplate` model. 
This `MailTemplate` model has a `mailable` property that corresponds to the `Mailable`'s class name. The `subject` and `template` properties are both used to store mustache template strings.

You might want to set up a seeder that seeds your application's necessary templates:

```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MailTemplatesSeeder extends Seeder
{
    public function run()
    {
        MailTemplate::create([
            'mailable' => App\Mails\WelcomeUserMail::class,
            'subject' => 'Welcome, {{ name }}',
            'template' => '<h1>Hello, {{ name }}!</h1>',
        ]);
    }
}
```

As you can see in the above example, you can use mustache template tags in both the subject and body of the mail template!

Let's have a look at the corresponding mailable:

```php
class WelcomeMail extends \Spatie\MailTemplates\TemplateMailable
{
    /** @var string */
    public $name;
    
    /** @var string */
    public $email;

    public function __construct(User $user)
    {
        $this->name = $user->name;
        $this->email = $user->email;
    }
}
```

By extending the `\Spatie\MailTemplates\TemplateMailable` class we'll render and send this mailable using the corresponding `MailTemplate`'s subject and body template. All public properties on the `WelcomeMail` will be passed to the template string.

### Custom `MailTemplate` model

The default `MailTemplate` model is sufficient for using _one_ database mail template for _one_ mailable. If you want to use different mail templates for the same mailable _or_ extend the `MailTemplate` model, we highly encourage you to publish the `mail_template` migration and extend the `MailTemplate` model.

Imagine an application like meetup.com that deals with different meetup groups. The application has a couple of different mailables like `NewMeetupPlannedMail`, `MeetupCancelledMail`, etc... 
Using this package we can give every meetup group its own `MeetupMailTemplate`s that contain their own copy for each mailable. The `MeetupMailTemplate` model would look something like this:

```php
class MeetupMailTemplate extends \Spatie\MailTemplates\MailTemplate
{
    public function meetupGroup(): BelongsTo
    {
        return $this->belongsTo(MeetupGroup::class);
    }
    
    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        return $query
            ->where('mailable', get_class($mailable))
            ->where('meetup_group_id', $mailable->getMeetupGroupId());
    }
    
    public function getLayout(): string
    {
        return $this->meetupGroup->mail_layout;
    }
}
``` 

As you can see, our `MeetupMailTemplate` model extends the package's `MailTemplate` and overrides a couple of methods. We've also added the relationship to the `MeetupGroup` that this mail template belongs to.

Using the `getLayout()` method we use the meetup group's custom mail header and footer. [Read more about adding a header and footer to a mail template here.](#adding-a-header-and-footer-around-a-mail-template) 

We've also extended the `scopeForMailable()` method. When sending a `TemplateMailable`, this scope will be used to fetch the corresponding mail template from the database. 
On top of the default `mailable` where-clause we've added a `meetup_group_id` where-clause that'll match the mailable's `meeting_group_id` to the the mail template.

Next, let's have a look at what our `NewMeetupPlannedMail` might look like:

```php
class NewMeetupPlannedMail extends \Spatie\MailTemplates\TemplateMailable
{
    // use our custom mail template model
    protected static $templateModel = MeetupMailTemplate::class;

    /** @var string */
    public $location;
    
    /** @var Meetup */
    protected $meetup; // protected property, we don't want this in the template data

    public function __construct(Meetup $meetup)
    {
        $this->meetup = $meetup;
        $this->location = $meetup->location;
    }
    
    // We need a method to get the meetup group id to use in the mail template's `scopeForMailable()` scope:
    public function getMeetupGroupId(): int
    {
        return $this->meetup->meetup_group_id;
    }  
}
```

When sending a `NewMeetupPlannedMail` the `MeetupMailTemplate` for the right meetup group will be used with that groups custom copy and mail layout. Pretty neat.

### Template variables

When building a UI for your mail templates you'll probably want to show a list of available variables near your wysiwyg-editor.
You can get the list of available variables from both the mailable and the mail template model using the `getVariables()`.

```php
WelcomeMail::getVariables();
// ['name', 'email']

MailTemplate::create(['mailable' => WelcomeMail::class, ... ])->getVariables();
// ['name', 'email']

MailTemplate::create(['mailable' => WelcomeMail::class, ... ])->variables;
// ['name', 'email']
```

### Adding a header and footer around a mail template

You can add a `getLayout()` method on either your mailable or your mail template. `getLayout()` should return a string layout containing the `{{{ body }}}` placeholder. 

When sending a `TemplateMailable` the compiled template will be rendered in place of the `{{{ body }}}` placeholder in the layout before being sent.

The following example will send a `WelcomeMail` using a template from the database and a layout.

```php
class WelcomeMail extends \Spatie\MailTemplates\TemplateMailable
{
    // ...
    
    public function getLayout(): string
    {
        /**
         * In your application you might want to fetch the layout from an external file or Blade view.
         * 
         * External file: `return file_get_contents(storage_path('mail-layouts/main.html'));`
         * 
         * Blade view: `return view('mailLayouts.main', $data)->render();`
         */
        
        return '<header>Site name!</header>{{{ body }}}<footer>Copyright 2018</footer>';
    }
}

MailTemplate::create([
    'mailable' => WelcomeMail::class,
    'template' => '<p>Welcome, {{ name }}!</p>', 
]);

Mail::to($user->email)->send(new WelcomeMail($user));
```

The HTML for the sent email will look like this:

```html
<header>Site name!</header>
<p>Welcome, John!</p>
<footer>Copyright 2018</footer>
```

#### Adding a layout based on your mail template model

You might want to use a different layout based on what mail template is being used. This can be done by adding the `getLayout()` method on your custom `MailTemplate` model instead. 

The following example uses a different layout based on what `EventMailTemplate` is being used. As you can see, the layout is stored in the database on a related `Event` model.

```php
class EventMailTemplate extends \Spatie\MailTemplates\MailTemplate
{
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getLayout(): string
    {
        return $this->event->mail_layout_html;
    }
}
``` 

### Translating mail templates

Out of the box this package doesn't support multi-langual templates. However, it integrates perfectly with [Laravel's localized mailables](https://laravel.com/docs/5.7/mail#localizing-mailables) and our own [laravel-translatable package](https://github.com/spatie/laravel-translatable).

Simply install the laravel-translatable package, publish the `create_mail_template_table` migration, change its `text` columns to `json` and extend the `MailTemplate` model like this:

```php
class MailTemplate extends \Spatie\MailTemplates\MailTemplate
{
    use HasTranslations;
    
    public $translatable = ['subject', 'template'];
}
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
