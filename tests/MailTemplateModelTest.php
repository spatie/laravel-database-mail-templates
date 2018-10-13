<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Tests\stubs\Mails\BasicMail;

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
        CustomTemplateModel::create([
            'mailable' => CustomTemplateModelMail::class,
            'template' => 'Hello, {{ name }}',
        ]);

        $renderedMail = (new CustomTemplateModelMail('John'))->render();

        $this->assertEquals('<main>Hello, John</main>', $renderedMail);
    }
}
