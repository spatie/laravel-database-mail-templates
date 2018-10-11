<?php

namespace Spatie\MailTemplates\Tests\stubs;

use Spatie\MailTemplates\Models\MailTemplate;

class CustomTemplateModel extends MailTemplate
{
    protected $table = 'mail_templates';

    public function getLayout(): string
    {
        return '<main>{{{ body }}}</main>';
    }
}
