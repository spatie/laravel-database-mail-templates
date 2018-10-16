<?php

namespace Spatie\MailTemplates\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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

    protected function getPackageProviders($app)
    {
        return [
            MailTemplatesServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

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
