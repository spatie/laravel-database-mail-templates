<?php

namespace Spatie\MailTemplates\Interfaces;

interface MailTemplateInterface
{
    /**
     * Get the mail subject.
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Get the mail template.
     *
     * @return string
     */
    public function getHtmlTemplate(): string;

    /**
     * Get the mail template.
     *
     * @return null|string
     */
    public function getTextTemplate(): ?string;
}
