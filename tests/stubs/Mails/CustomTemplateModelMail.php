<?php

namespace Spatie\MailTemplates\Tests\stubs\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;
use Spatie\MailTemplates\Tests\stubs\Models\CustomMailTemplate;

class CustomTemplateModelMail extends TemplateMailable
{
    use Queueable;
    use SerializesModels;

    protected static $templateModel = CustomMailTemplate::class;

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
