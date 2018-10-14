<?php

namespace Spatie\MailTemplates\Tests\stubs\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;
use Spatie\MailTemplates\Models\MailTemplate;

class BasicMail extends TemplateMailable
{
    use Queueable, SerializesModels;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public function __construct(string $name = 'John', string $email = '')
    {
        $this->name = $name;
        $this->email = $email;

        // Reset the model every time the mailable is used
        $this->useTemplateModel(MailTemplate::class);
    }

    public function useTemplateModel(string $templateModel): self
    {
        static::$templateModel = $templateModel;

        return $this;
    }
}
