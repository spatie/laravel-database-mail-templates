<?php

namespace Spatie\MailTemplates\Exceptions;

use Exception;

class InvalidTemplateMailable extends Exception
{
    public static function layoutDoesNotContainABodyPlaceHolder(string $mailable)
    {
        $mailable = class_basename($mailable);

        throw new static("The layout for mailable `{$mailable}` does not contain a `{{{ body }}}` placeholder");
    }
}
