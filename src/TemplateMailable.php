<?php

namespace Spatie\MailTemplates;

use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use ReflectionClass;
use ReflectionProperty;
use Spatie\MailTemplates\Models\MailTemplate;

abstract class TemplateMailable extends Mailable
{
    public static function getVariables(): array
    {
        return static::getPublicProperties();
    }

    protected function buildView()
    {
        $renderer = $this->getMailTemplateRenderer();

        $viewData = $this->buildViewData();

        $html = $renderer ->render($viewData);

        $text = $renderer->renderTextView($viewData);

        return array_filter([
            'html' => new HtmlString($html),
            'text' => $text,
        ]);
    }

    protected function buildSubject($message)
    {
        if ($this->subject) {
            $message->subject($this->subject);

            return $this;
        }

        if (MailTemplate::findForMailable($this)->subject)
        {
            $subject = $this
                ->getMailTemplateRenderer()
                ->renderSubject($this->buildViewData());

            $message->subject($subject);

            return $this;
        }

        return parent::buildSubject($message);
    }

    public function getLayout(): ?string
    {
        return null;
    }

    protected static function getPublicProperties(): array
    {
        $class = new ReflectionClass(static::class);

        return collect($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map->getName()
            ->values()
            ->all();
    }

    protected function getMailTemplateRenderer(): TemplateMailableRenderer
    {
        return app(TemplateMailableRenderer::class, ['templateMailable' => $this]);
    }
}
