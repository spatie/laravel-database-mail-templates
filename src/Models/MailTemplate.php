<?php

namespace Spatie\MailTemplates\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MailTemplates\Exceptions\MissingMailTemplate;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;

class MailTemplate extends Model implements MailTemplateInterface
{
    protected $guarded = [];

    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        return $query->where('mailable', get_class($mailable));
    }

    public static function findForMailable(Mailable $mailable): self
    {
        $mailTemplate = static::forMailable($mailable)->first();

        if (! $mailTemplate) {
            throw MissingMailTemplate::forMailable($mailable);
        }

        return $mailTemplate;
    }

    public function getHtmlLayout(): ?string
    {
        return null;
    }

    public function getTextLayout(): ?string
    {
        return null;
    }

    public function getVariables(): array
    {
        $mailableClass = $this->mailable;

        if (! class_exists($mailableClass)) {
            return [];
        }

        return $mailableClass::getVariables();
    }

    public function getVariablesAttribute(): array
    {
        return $this->getVariables();
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getHtmlTemplate(): string
    {
        return $this->html_template;
    }

    public function getTextTemplate(): ?string
    {
        return $this->text_template;
    }
}
