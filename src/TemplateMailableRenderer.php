<?php

namespace Spatie\MailTemplates;

use Mustache_Engine;
use Spatie\MailTemplates\Exceptions\CannotRenderTemplateMailable;

class TemplateMailableRenderer
{
    /** @var \Spatie\MailTemplates\TemplateMailable */
    protected $templateMailable;

    /** @var \Spatie\MailTemplates\Interfaces\MailTemplateInterface */
    protected $mailTemplate;

    /** @var \Mustache_Engine */
    protected $mustache;

    public function __construct(TemplateMailable $templateMailable, Mustache_Engine $mustache)
    {
        $this->templateMailable = $templateMailable;
        $this->mustache = $mustache;
        $this->mailTemplate = $templateMailable->getMailTemplate();
    }

    public function render(array $data = []): string
    {
        $html = $this->mustache->render(
            $this->mailTemplate->template(),
            $data
        );

        return $this->renderInLayout($html, $data);
    }

    public function renderSubject(array $data = []): string
    {
        return $this->mustache->render(
            $this->mailTemplate->subject(),
            $data
        );
    }

    protected function renderInLayout(string $html, array $data = []): string
    {
        $layout = $this->templateMailable->getLayout()
            ?? $this->mailTemplate->getLayout()
            ?? '{{{ body }}}';

        $this->guardAgainstInvalidLayout($layout);

        $data = array_merge(['body' => $html], $data);

        return $this->mustache->render($layout, $data);
    }

    protected function guardAgainstInvalidLayout(string $layout): void
    {
        if ( ! str_contains($layout, [
            '{{{body}}}',
            '{{{ body }}}',
            '{{body}}',
            '{{ body }}',
        ])) {
            throw CannotRenderTemplateMailable::layoutDoesNotContainABodyPlaceHolder($this->templateMailable);
        }
    }
}
