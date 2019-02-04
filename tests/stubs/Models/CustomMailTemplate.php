<?php

namespace Spatie\MailTemplates\Tests\stubs\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MailTemplates\Models\MailTemplate;

class CustomMailTemplate extends MailTemplate
{
    protected $table = 'custom_mail_templates';

    public function getHtmlLayout(): string
    {
        return '<main>{{{ body }}}</main>';
    }

    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        return $query->where('use', true);
    }
}
