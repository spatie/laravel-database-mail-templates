<?php

namespace Spatie\MailTemplates\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MailTemplates\Exceptions\MissingMailTemplate;

class MailTemplate extends Model
{
    protected $guarded = [];

    public static function findForMailable(Mailable $mailable): self
    {
        $mailTemplate = static::query()
            ->where('mailable', get_class($mailable)) // + scope
            ->first();

        if (! $mailTemplate) {
            throw MissingMailTemplate::forMailable($mailable);
        }

        return $mailTemplate;
    }
}
