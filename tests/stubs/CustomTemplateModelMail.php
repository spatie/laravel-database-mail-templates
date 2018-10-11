<?php

namespace Spatie\MailTemplates\Tests\stubs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class CustomTemplateModelMail extends TemplateMailable
{
    use Queueable, SerializesModels;

    protected static $templateModel = CustomTemplateModel::class;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public function __construct(string $name = 'John', string $email = '')
    {
        $this->name = $name;
        $this->email = $email;
    }
}
