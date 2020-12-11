<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Exceptions\CannotRenderTemplateMailable;
use Spatie\MailTemplates\Exceptions\MissingMailTemplate;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Models\MailTemplateType;
use Spatie\MailTemplates\Tests\stubs\Mails\BadLayoutMail;
use Spatie\MailTemplates\Tests\stubs\Mails\BasicMail;
use Spatie\MailTemplates\Tests\stubs\Mails\LayoutMail;

class TemplateMailableTest extends TestCase
{
    /** @test */
    public function it_can_render_a_mailable()
    {
        $type = MailTemplateType::create([
            'name' => 'Basic',
        ]);

        MailTemplate::create([
            'mailable' => BasicMail::class,
            'html_template' => 'Hello, {{ name }}',
            'type_id' => $type->id,
        ]);

        $renderedMail = (new BasicMail('John'))->render();

        $this->assertEquals('Hello, John', $renderedMail);
    }

    /** @test */
    public function it_can_get_the_available_template_variables_for_a_mailable()
    {
        $variables = BasicMail::getVariables();

        $this->assertEquals(['name', 'email'], $variables);
    }

    /** @test */
    public function it_can_render_a_mailable_with_a_layout()
    {
        $this->createMailTemplateForMailable(LayoutMail::class);

        $renderedMail = (new LayoutMail('John'))->render();

        $this->assertEquals('<main>Hello, John</main>', $renderedMail);
    }

    /** @test */
    public function it_throws_an_exception_if_the_layout_does_not_contain_a_body_tag()
    {
        $this->expectException(CannotRenderTemplateMailable::class);

        $this->createMailTemplateForMailable(BadLayoutMail::class);

        (new BadLayoutMail('John'))->render();
    }

    /** @test */
    public function it_throws_an_exception_if_no_mail_template_exists_for_mailable()
    {
        $this->expectException(MissingMailTemplate::class);

        (new BasicMail('John'))->render();
    }
}
