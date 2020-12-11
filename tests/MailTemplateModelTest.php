<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Models\MailTemplateType;
use Spatie\MailTemplates\Tests\stubs\Mails\BasicMail;
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
    public function it_gets_an_empty_template_variable_array_for_a_mail_template_with_a_missing_mailable()
    {
        $mailTemplate = $this->createMailTemplateForMailable('\Non\Existent\Mailable');

        $variables = $mailTemplate->getVariables();

        $this->assertEquals([], $variables);
    }

    /** @test */
    public function it_can_render_a_mail_template_with_a_layout()
    {
        $type = MailTemplateType::create([
            'name' => 'Basic',
        ]);

        LayoutMailTemplate::create([
            'mailable' => BasicMail::class,
            'html_template' => 'Hello, {{ name }}',
            'type_id' => $type->id,
        ]);

        $renderedMail = (new BasicMail('John'))->useTemplateModel(LayoutMailTemplate::class)->render();

        $this->assertEquals('<main>Hello, John</main>', $renderedMail);
    }
}
