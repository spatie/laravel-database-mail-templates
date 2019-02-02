<?php

namespace Spatie\MailTemplates\Interfaces;

interface MailTemplateInterface
{
    /**
     * Get the mail subject.
     * @return string
     */
    public function getSubject(): string;

    /**
     * Get the mail template.
     * @return string
     */
    public function getTemplate(): string;
}
