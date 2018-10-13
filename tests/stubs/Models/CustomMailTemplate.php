<?php

namespace Spatie\MailTemplates\Tests\stubs\Models;

use Spatie\MailTemplates\Models\MailTemplate;

class CustomMailTemplate extends MailTemplate
{
    protected $table = 'mail_templates';

    public function getLayout(): string
    {
        return '<main>{{{ body }}}</main>';
    }
}
