<?php

namespace Spatie\MailTemplates\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Mail\Mailable;
use Spatie\MailTemplates\MailTemplatesServiceProvider;
use Spatie\MailTemplates\Models\MailTemplate;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

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

    public function createMailTemplateForMailable(string $mailable): MailTemplate
    {
        return MailTemplate::create([
            'mailable' => $mailable,
            'template' => 'Hello, {{ name }}',
        ]);
    }
}
