<?php

namespace Spatie\MailTemplates\Tests;

use Illuminate\Foundation\Application;
use Spatie\MailTemplates\Models\MailTemplate;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\MailTemplates\MailTemplatesServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    protected function getPackageProviders($app)
    {
        return [
            MailTemplatesServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->artisan('migrate');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function createMailTemplateForMailable(
        string $mailable,
        ?string $mailTemplate = null,
        array $attributes = []
    ): MailTemplate {
        $mailTemplate = $mailTemplate ?? MailTemplate::class;

        return $mailTemplate::create(array_merge([
            'mailable' => $mailable,
            'template' => 'Hello, {{ name }}',
        ], $attributes));
    }
}
