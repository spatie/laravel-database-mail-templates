<?php

namespace Spatie\MailTemplates;

use Illuminate\Mail\Markdown;
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

    /** @var Markdown */
    protected $markdown;

    public function __construct(TemplateMailable $templateMailable, Mustache_Engine $mustache, Markdown $markdown)
    {
        $this->templateMailable = $templateMailable;
        $this->mustache = $mustache;
        $this->markdown = $markdown;

        $templateModel = $this->templateMailable->getTemplateModel();
        $this->mailTemplate = $templateModel::findForMailable($templateMailable);
    }

    public function render(array $data = []): string
    {
        $renderer = ($this->mailTemplate->isMarkdown()
            ? $this->markdown->theme($this->mailTemplate->markdown_theme ?? 'default')
            : $this->mustache);

        $html = $renderer->render(
            $this->mailTemplate->template,
            $data
        );

        return $this->renderInLayout($html, $data);
    }

    public function renderTextView(array $data = []): ?string
    {
        if ($this->mailTemplate->isMarkdown()) {
            return $this->templateMailable->textView
                ?? $this->markdown->renderText($this->mailTemplate->template, $data);
        }

        return $this->templateMailable->textView ?? null;
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
