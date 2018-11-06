<?php

namespace Spatie\MailTemplates\Interfaces;

interface MailTemplateInterface
{
    /**
     * Get the mail subject.
     * @return string
     */
    public function subject(): string;

    /**
     * Get the mail template.
     * @return string
     */
    public function template(): string;
}