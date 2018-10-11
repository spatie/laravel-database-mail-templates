<?php

namespace Spatie\MailTemplates;

use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use Spatie\MailTemplates\Exceptions\InvalidTemplateMailable;
use Spatie\MailTemplates\Models\MailTemplate;
use Mustache_Engine;

abstract class TemplateMailable extends Mailable
{
    /** @var MailTemplate */
    protected $mailTemplate;

    /** @var Mustache_Engine */
    protected $mustache;

    public function __construct()
    {
        $this->mustache = new Mustache_Engine();

        $this->mailTemplate = MailTemplate::findForMailable($this);

        $this->html($this->mailTemplate->template);

        $this->subject($this->mailTemplate->subject);
    }

    abstract public static function getVariables(): array;

    protected function buildView()
    {
        $html = $this->renderInLayout($this->html);

        $html = $this->mustache->render($html, $this->viewData);

        return [
            'html' => new HtmlString($html),
        ];
    }

    protected function renderInLayout(string $html): string
    {
        $layout = $this->getLayout() ?? '{{{ body }}}';

        if (! str_contains($layout, ['{{{body}}}', '{{{ body }}}', '{{body}}', '{{ body }}'])) {
            throw InvalidTemplateMailable::layoutDoesNotContainABodyPlaceHolder($this);
        }

        return $this->mustache->render($layout, ['body' => $html]);
    }

    protected function getLayout(): string
    {
        return '<div>{{{ body }}}</div>';
    }
}
