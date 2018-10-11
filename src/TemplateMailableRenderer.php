<?php

namespace Spatie\MailTemplates;

use Illuminate\Support\Facades\Mail;
use Mustache_Engine;
use Spatie\MailTemplates\Exceptions\CannotRenderTemplateMailable;
use Spatie\MailTemplates\Models\MailTemplate;

class TemplateMailableRenderer
{
    /** @var TemplateMailable */
    protected $templateMailable;

    /** @var MailTemplate */
    protected $mailTemplate;

    /** @var Mustache_Engine */
    protected $mustache;

    public function __construct(TemplateMailable $templateMailable, Mustache_Engine $mustache)
    {
        $this->mustache = $mustache;

        $this->templateMailable = $templateMailable;

        $this->mailTemplate = MailTemplate::findForMailable($templateMailable);
    }

    public function render(array $data = []): string
    {
        $html = $this->mustache->render(
            $this->mailTemplate->template,
            $data
        );

        return $this->renderInLayout($html);
    }

    public function renderSubject(array $data = []): string
    {
        return $this->mustache->render(
            $this->mailTemplate->subject,
            $data
        );
    }

    protected function renderInLayout(string $html, array $data = []): string
    {
        $layout = $this->templateMailable->getLayout()
            ?? $this->mailTemplate->getLayout()
            ?? '{{{ body }}}';

        // TODO: Regex for finding {{{ body }}} in layout string
        if ($layout && ! str_contains($layout, ['{{{body}}}', '{{{ body }}}', '{{body}}', '{{ body }}'])) {
            throw CannotRenderTemplateMailable::layoutDoesNotContainABodyPlaceHolder($this->templateMailable, $this->mailTemplate, $layout);
        }

        $data = array_merge(['body' => $html], $data);

        return $this->mustache->render($layout, $data);
    }
}
