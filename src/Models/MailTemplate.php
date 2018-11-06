<?php

namespace Spatie\MailTemplates\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    public function getLayout(): ?string
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

    public function subject(): string
    {
        return $this->subject;
    }

    public function template(): string
    {
        return $this->template;
    }
}
