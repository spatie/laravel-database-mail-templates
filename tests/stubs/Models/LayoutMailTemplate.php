<?php

namespace Spatie\MailTemplates\Tests\stubs\Models;

use Spatie\MailTemplates\Models\MailTemplate;

class LayoutMailTemplate extends MailTemplate
{
    protected $table = 'mail_templates';

    public function getHtmlLayout(): string
    {
        return '<main>{{{ body }}}</main>';
    }
}
