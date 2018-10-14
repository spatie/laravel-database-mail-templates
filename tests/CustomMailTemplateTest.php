<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Tests\stubs\Mails\BasicMail;
use Spatie\MailTemplates\Tests\stubs\Mails\CustomTemplateModelMail;
use Spatie\MailTemplates\Tests\stubs\Models\CustomMailTemplate;

class CustomMailTemplateTest extends TestCase
{
    /** @test */
    public function it_can_render_a_mailable_using_a_custom_mail_template()
    {
        $this->createMailTemplateForMailable(BasicMail::class, CustomMailTemplate::class, ['use' => true]);

        $renderedMail = (new BasicMail())->useTemplateModel(CustomMailTemplate::class)->render();

        $this->assertEquals('<main>Hello, John</main>', $renderedMail);
    }

    /** @test */
    public function it_can_resolve_a_mail_template_for_a_mailable_based_on_a_custom_scope()
    {
        $this->createMailTemplateForMailable(CustomTemplateModelMail::class, CustomMailTemplate::class, ['use' => false]);
        $mailTemplate = $this->createMailTemplateForMailable(CustomTemplateModelMail::class, CustomMailTemplate::class, ['use' => true]);

        $mailable = new CustomTemplateModelMail();

        $resolvedMailTemplate = CustomMailTemplate::findForMailable($mailable);

        $this->assertEquals($mailTemplate->id, $resolvedMailTemplate->id);
    }
}
