<?php

namespace Spatie\MailTemplates\Exceptions;

use Exception;
use Spatie\MailTemplates\TemplateMailable;
use Spatie\MailTemplates\Models\MailTemplate;

class CannotRenderTemplateMailable extends Exception
{
    public static function layoutDoesNotContainABodyPlaceHolder(TemplateMailable $templateMailable, MailTemplate $mailTemplate, string $layoutHtml)
    {
        $mailableClass = class_basename($templateMailable);

        return new static("The layout for mailable `{$mailableClass}` does not contain a `{{{ body }}}` placeholder");
    }
}
