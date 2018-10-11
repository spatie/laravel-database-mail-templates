<?php

namespace Spatie\MailTemplates\Exceptions;

use Exception;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\TemplateMailable;

class CannotRenderTemplateMailable extends Exception
{
    /** @var TemplateMailable */
    public $templateMailable;

    /** @var MailTemplate */
    public $mailTemplate;

    /** @var string */
    public $layoutHtml;

    public function __construct(TemplateMailable $templateMailable, MailTemplate $mailTemplate, string $layoutHtml)
    {
        $this->templateMailable = $templateMailable;
        $this->mailTemplate = $mailTemplate;
        $this->layoutHtml = $layoutHtml;

        $mailableClass = class_basename($this->templateMailable);

        parent::__construct("The layout for mailable `{$mailableClass}` does not contain a `{{{ body }}}` placeholder");
    }

    public static function layoutDoesNotContainABodyPlaceHolder(TemplateMailable $templateMailable, MailTemplate $mailTemplate, string $layoutHtml)
    {
        throw new static(...func_get_args());
    }
}
