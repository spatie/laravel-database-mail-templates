<?php

namespace Spatie\MailTemplates\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MailTemplates\Exceptions\MissingMailTemplate;

class MailTemplate extends Model
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

    public function isMarkdown(): bool
    {
        return $this->type === 'markdown';
    }

    public function getVariables(): array
    {
        $mailableClass = $this->mailable;

        return $mailableClass::getVariables();
    }

    public function getVariablesAttribute(): array
    {
        return $this->getVariables();
    }
}
