# Render Laravel mailables based on a mail template stored in the database

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-database-mail-templates)
![Test Status](https://img.shields.io/github/workflow/status/spatie/laravel-database-mail-templates/run-tests?label=tests&style=flat-square)
![PHP CS Fixer Status](https://img.shields.io/github/workflow/status/spatie/laravel-database-mail-templates/Check%20&%20fix%20styling?label=code%20style&style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-database-mail-templates.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-database-mail-templates)

Render Laravel mailables using a template stored in the database.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-database-mail-templates.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-database-mail-templates)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Quick example

The following example will send a `WelcomeMail` using a template stored in the database and wrapped in an HTML layout.

```php
use Spatie\MailTemplates\TemplateMailable;

class WelcomeMail extends TemplateMailable
{
    /** @var string */
    public $name;

    public function __construct(User $user)
    {
        $this->name = $user->name;
    }
    
    public function getHtmlLayout(): string
    {
        $pathToLayout = storage_path('mail-layouts/main.html');
    
        return file_get_contents($pathToLayout);
    }
}

MailTemplate::create([
    'mailable' => WelcomeMail::class,
    'subject' => 'Welcome, {{ name }}',
    'html_template' => '<p>Hello, {{ name }}.</p>',
    'text_template' => 'Hello, {{ name }}.'
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

Publish and run the database migrations:

```bash
php artisan vendor:publish --provider="Spatie\MailTemplates\MailTemplatesServiceProvider" --tag="migrations"
```

If you want to use the [default `MailTemplate` model](#default-mailtemplate-model), all that's left to do is run `php artisan migrate` to create the `mail_templates` table. 

If you plan on creating a [custom `MailTemplate` model](#custom-mailtemplate-model) continue by modifying the migration and creating your custom model before running `php artisan migrate`.

## Usage

After installing the package and running the migrations you'll have a new table in your database called `mail_templates`. This table will be used by the `MailTemplate` model.

The default `MailTemplate` has a `mailable` property that corresponds to the `Mailable`'s class name. It also has a `subject` and `body` property which are both used to store [mustache template](http://mustache.github.io/) strings.

You might want to set up a seeder that seeds your application's necessary templates:

```php
use Illuminate\Database\Seeder;

class MailTemplatesSeeder extends Seeder
{
    public function run()
    {
        MailTemplate::create([
            'mailable' => \App\Mails\WelcomeMail::class,
            'subject' => 'Welcome, {{ name }}',
            'html_template' => '<h1>Hello, {{ name }}!</h1>',
            'text_template' => 'Hello, {{ name }}!',
        ]);
    }
}
```

As you can see in the above example, you can use mustache template tags in both the subject and body of the mail template!

Let's have a look at the corresponding mailable:

```php
use TemplateMailable;

class WelcomeMail extends TemplateMailable
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

By extending the `\Spatie\MailTemplates\TemplateMailable` class this mailable will be rendered using the corresponding `MailTemplate`. All public properties on the `WelcomeMail` will be available in the template.

### Customizing the `MailTemplate` model

The default `MailTemplate` model is sufficient for using _one_ database mail template for _one_ mailable. If you want to use multiple mail templates for the same mailable _or_ extend the `MailTemplate` model, we highly encourage you to publish the `mail_template` migration and create your own mail template model by extending `MailTemplate`. Make sure to implement the `MailTemplateInterface` interface as well.

Imagine an application like [meetup.com](https://meetup.com) that deals with different meetup groups. The application has a couple of different mailables like `NewMeetupPlannedMail` and `MeetupCancelledMail` to inform users of new meetups.
Using this package we can create a `MeetupMailTemplate` for each meetup group. This way each group can add their own copy in the template. The `MeetupMailTemplate` model would look something like this:

```php
use Spatie\MailTemplates\MailTemplate;

class MeetupMailTemplate extends MailTemplate implements MailTemplateInterface
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
    
    public function getHtmlLayout(): string
    {
        return $this->meetupGroup->mail_layout;
    }
}
``` 

`MeetupMailTemplate` extends the package's `MailTemplate` and overrides a couple of methods. We've also added the relationship to the `MeetupGroup` that this mail template belongs to.

By extending the `getHtmlLayout()` method we can provide the group's custom mail header and footer. [Read more about adding a header and footer to a mail template.](#adding-a-header-and-footer-around-a-mail-template) 

We've also extended the `scopeForMailable()` method which is used to fetch the corresponding mail template from the database. 
On top of the default `mailable` where-clause we've added a `meetup_group_id` where-clause that'll query for the mailable's `meeting_group_id`.

Next, let's have a look at what our `NewMeetupPlannedMail` might look like:

```php
use Spatie\MailTemplates\TemplateMailable;

class NewMeetupPlannedMail extends TemplateMailable
{
    // use our custom mail template model
    protected static $templateModelClass = MeetupMailTemplate::class;

    /** @var string */
    public $location;
    
    /** @var \App\Models\Meetup */
    protected $meetup; // protected property, we don't want this in the template data

    public function __construct(Meetup $meetup)
    {
        $this->meetup = $meetup;
        $this->location = $meetup->location;
    }
    
    // provide a method to get the meetup group id so we can use it in MeetupMailTemplate
    public function getMeetupGroupId(): int
    {
        return $this->meetup->meetup_group_id;
    }  
}
```
When sending a `NewMeetupPlannedMail` the right `MeetupMailTemplate` for the meetup group will be used with its own custom copy and mail layout. Pretty neat.

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

You can extend the `getHtmlLayout()` method on either a template mailable or a mail template. `getHtmlLayout()` should return a string layout containing the `{{{ body }}}` placeholder. 

When sending a `TemplateMailable` the compiled template will be rendered inside of the `{{{ body }}}` placeholder in the layout before being sent.

The following example will send a `WelcomeMail` using a template wrapped in a layout.

```php
use Spatie\MailTemplates\TemplateMailable;

class WelcomeMail extends TemplateMailable
{
    // ...
    
    public function getHtmlLayout(): string
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
    'html_template' => '<p>Welcome, {{ name }}!</p>', 
]);

Mail::to($user->email)->send(new WelcomeMail($user));
```

The rendered HTML for the sent email will look like this:

```html
<header>Site name!</header>
<p>Welcome, John!</p>
<footer>Copyright 2018</footer>
```

#### Adding a layout to a mail template model

It is also possible to extend the `getHtmlLayout()` method of the `MailTemplate` model (instead of extending `getHtmlLayout()`on the mailable).

You might for example want to use a different layout based on a mail template model property. This can be done by adding the `getHtmlLayout()` method on your custom `MailTemplate` model instead. 

The following example uses a different layout based on what `EventMailTemplate` is being used. As you can see, in this case the layout is stored in the database on a related `Event` model.

```php
use Spatie\MailTemplates\MailTemplate;

class EventMailTemplate extends MailTemplate
{
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getHtmlLayout(): string
    {
        return $this->event->mail_layout_html;
    }
}
``` 

### Translating mail templates

Out of the box this package doesn't support multi-langual templates. However, it integrates perfectly with [Laravel's localized mailables](https://laravel.com/docs/5.7/mail#localizing-mailables) and our own [laravel-translatable package](https://github.com/spatie/laravel-translatable).

Simply install the laravel-translatable package, publish the `create_mail_template_table` migration, change its `text` columns to `json` and extend the `MailTemplate` model like this:

```php
use \Spatie\MailTemplates\MailTemplate;

class MailTemplate extends MailTemplate
{
    use HasTranslations;
    
    public $translatable = ['subject', 'html_template'];
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

## Credits

- [Alex Vanderbist](https://github.com/alexvanderbist)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
