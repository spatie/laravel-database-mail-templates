<?php

namespace Spatie\MailTemplates;

use ReflectionClass;
use ReflectionProperty;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use Spatie\MailTemplates\Models\MailTemplate;

abstract class TemplateMailable extends Mailable
{
    protected static $templateModel = MailTemplate::class;

    public static function getVariables(): array
    {
        return static::getPublicProperties();
    }

    public function getTemplateModel(): string
    {
        return static::$templateModel;
    }

    protected function buildView()
    {
        $renderer = $this->getMailTemplateRenderer();

        $viewData = $this->buildViewData();

        $html = $renderer->render($viewData);

        return array_filter([
            'html' => new HtmlString($html),
            'text' => $this->textView ?? null,
        ]);
    }

    protected function buildSubject($message)
    {
        if ($this->subject) {
            $message->subject($this->subject);

            return $this;
        }

        if (MailTemplate::findForMailable($this)->subject) {
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

    public function build()
    {
        return $this;
    }

    protected static function getPublicProperties(): array
    {
        $class = new ReflectionClass(static::class);

        return collect($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map->getName()
            ->diff(static::getIgnoredPublicProperties())
            ->values()
            ->all();
    }

    protected static function getIgnoredPublicProperties(): array
    {
        $mailableClass = new ReflectionClass(Mailable::class);
        $queueableClass = new ReflectionClass(Queueable::class);

        return collect()
            ->merge($mailableClass->getProperties(ReflectionProperty::IS_PUBLIC))
            ->merge($queueableClass->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map->getName()
            ->values()
            ->all();
    }

    protected function getMailTemplateRenderer(): TemplateMailableRenderer
    {
        return app(TemplateMailableRenderer::class, ['templateMailable' => $this]);
    }
}
