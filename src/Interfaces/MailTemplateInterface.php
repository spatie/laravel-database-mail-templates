<?php

namespace Spatie\MailTemplates\Interfaces;

use Illuminate\Contracts\Mail\Mailable;

interface MailTemplateInterface
{
    public static function findForMailable(Mailable $mailable);

    /**
     * Get the mail subject.
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Get the HTML mail template.
     *
     * @return string
     */
    public function getHtmlTemplate(): string;

    /**
     * Get the text mail template.
     *
     * @return null|string
     */
    public function getTextTemplate(): ?string;

    /**
     * Get the code of the mail template.
     *
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * Get the label of the mail template.
     *
     * @return null|string
     */
    public function getLabel(): ?string;
}
