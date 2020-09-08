<?php

namespace Spatie\MailTemplates;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use ReflectionClass;
use ReflectionProperty;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate;

abstract class TemplateMailable extends Mailable
{
    protected static $templateModelClass = MailTemplate::class;

    /** @var MailTemplateInterface */
    protected $mailTemplate;

    public static function getVariables(): array
    {
        return static::getPublicProperties();
    }

    public function getMailTemplate(): MailTemplateInterface
    {
        return $this->mailTemplate ?? $this->resolveTemplateModel();
    }

    protected function resolveTemplateModel(): MailTemplateInterface
    {
        return $this->mailTemplate = static::$templateModelClass::findForMailable($this);
    }

    protected function buildView()
    {
        $renderer = $this->getMailTemplateRenderer();

        $viewData = $this->buildViewData();

        $html = $renderer->renderHtmlLayout($viewData);
        $text = $renderer->renderTextLayout($viewData);

        return array_filter([
            'html' => new HtmlString($html),
            'text' => new HtmlString($text),
        ]);
    }

    protected function buildSubject($message)
    {
        if ($this->subject) {
            $message->subject($this->subject);

            return $this;
        }

        if ($this->getMailTemplate()->getSubject()) {
            $subject = $this
                ->getMailTemplateRenderer()
                ->renderSubject($this->buildViewData());

            $message->subject($subject);

            return $this;
        }

        return parent::buildSubject($message);
    }

    public function getHtmlLayout(): ?string
    {
        return null;
    }

    public function getTextLayout(): ?string
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
