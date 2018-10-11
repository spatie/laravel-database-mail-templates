<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Tests\stubs\BasicMail;
use Spatie\MailTemplates\Tests\stubs\LayoutMail;
use Spatie\MailTemplates\Tests\stubs\BadLayoutMail;
use Spatie\MailTemplates\Exceptions\MissingMailTemplate;
use Spatie\MailTemplates\Exceptions\CannotRenderTemplateMailable;

class TemplateMailableTest extends TestCase
{
    /** @test */
    public function it_can_render_a_mailable()
    {
        MailTemplate::create([
            'mailable' => BasicMail::class,
            'template' => 'Hello, {{ name }}',
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
