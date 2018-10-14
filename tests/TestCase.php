<?php

namespace Spatie\MailTemplates\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
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

        Schema::create('custom_mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mailable');
            $table->text('subject')->nullable();
            $table->text('template');
            $table->boolean('use')->default(false);
            $table->timestamps();
        });
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
