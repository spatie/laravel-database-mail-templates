<?php

namespace Spatie\MailTemplates\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\MailTemplates\MailTemplatesServiceProvider;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Models\MailTemplateType;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Schema::create('custom_mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('mailable');
            $table->text('subject')->nullable();
            $table->text('html_template');
            $table->text('text_template')->nullable();
            $table->boolean('use')->default(false);
            $table->string('code')->nullable();
            $table->string('label')->nullable();

            $table->unsignedInteger('type_id');
            $table->foreign('type_id')
                ->references('id')
                ->on('mail_template_types')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function createMailTemplateForMailable(
        string $mailable,
        ?string $mailTemplate = null,
        array $attributes = []
    ): MailTemplate {
        $mailTemplate = $mailTemplate ?? MailTemplate::class;

        $type = MailTemplateType::create([
            'name' => 'Basic',
        ]);

        return $mailTemplate::create(array_merge([
            'mailable' => $mailable,
            'html_template' => 'Hello, {{ name }}',
            'type_id' => $type->id,
        ], $attributes));
    }
}
