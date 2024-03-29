<?php

namespace Spatie\MailTemplates\Tests\stubs\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class LayoutMail extends TemplateMailable
{
    use Queueable;
    use SerializesModels;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public function __construct(string $name = 'John', string $email = '')
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getHtmlLayout(): string
    {
        return '<main>{{{ body }}}</main>';
    }
}
