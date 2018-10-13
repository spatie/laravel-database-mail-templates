<?php

namespace Spatie\MailTemplates\Tests;

use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Tests\stubs\Mails\CustomTemplateModelMail;
use Spatie\MailTemplates\Tests\stubs\Models\CustomMailTemplate;

class CustomMailTemplateTest extends TestCase
{
    /** @test */
    public function it_can_resolve_a_mail_template_for_a_mailable_based_on_a_custom_scope()
    {
        $mailTemplate = $this->createMailTemplateForMailable(CustomTemplateModelMail::class, CustomMailTemplate::class);

        $mailable = new CustomTemplateModelMail();

        $resolvedMailTemplate = CustomMailTemplate::findForMailable($mailable);

        $this->assertEquals($mailTemplate->id, $resolvedMailTemplate->id);
    }
}
