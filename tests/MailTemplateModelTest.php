<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Tests\stubs\Mails\BasicMail;
use Spatie\MailTemplates\Tests\stubs\Mails\CustomTemplateModelMail;
use Spatie\MailTemplates\Tests\stubs\Mails\LayoutMail;
use Spatie\MailTemplates\Tests\stubs\Models\CustomMailTemplate;
use Spatie\MailTemplates\Tests\stubs\Models\LayoutMailTemplate;

class MailTemplateModelTest extends TestCase
{
    /** @test */
    public function it_can_resolve_the_right_mail_template_for_a_mailable()
    {
        $mailTemplate = $this->createMailTemplateForMailable(BasicMail::class);

        $mailable = new BasicMail('John');

        $resolvedMailTemplate = MailTemplate::findForMailable($mailable);

        $this->assertEquals($mailTemplate->id, $resolvedMailTemplate->id);
    }

    /** @test */
    public function it_can_get_the_available_template_variables_for_a_mail_template()
    {
        $basicMail = new BasicMail();

        $this->createMailTemplateForMailable(BasicMail::class);

        $variables = MailTemplate::findForMailable($basicMail)->getVariables();

        $this->assertEquals(['name', 'email'], $variables);
    }

    /** @test */
    public function it_can_render_a_mail_template_with_a_layout()
    {
        LayoutMailTemplate::create([
            'mailable' => BasicMail::class,
            'template' => 'Hello, {{ name }}',
        ]);

        $renderedMail = (new BasicMail('John'))->useTemplateModel(LayoutMailTemplate::class)->render();

        $this->assertEquals('<main>Hello, John</main>', $renderedMail);
    }
}
