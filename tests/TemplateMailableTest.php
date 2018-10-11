<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Exceptions\CannotRenderTemplateMailable;
use Spatie\MailTemplates\Exceptions\MissingMailTemplate;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Tests\stubs\User;
use Spatie\MailTemplates\Tests\stubs\WelcomeMail;

class TemplateMailableTest extends TestCase
{
    /** @var  User */
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = new User('John Doe');
    }

    /** @test */
    public function it_can_render_a_mailable()
    {
        MailTemplate::create([
            'mailable' => WelcomeMail::class,
            'template' => 'Hello, {{ name }}',
        ]);

        $renderedMail = (new WelcomeMail($this->user))->render();

        $this->assertEquals('Hello, John Doe', $renderedMail);
    }

    /** @test */
    public function it_can_render_a_mailable_with_a_layout()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_render_a_mail_template_with_a_layout()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_throws_an_exception_if_no_mail_template_exists_for_mailable()
    {
        $this->expectException(MissingMailTemplate::class);

        (new WelcomeMail($this->user))->render();
    }

    /** @test */
    public function it_throws_an_exception_if_the_layout_does_not_contain_a_body_tag()
    {
        $this->expectException(CannotRenderTemplateMailable::class);

        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_get_the_available_template_variables_for_a_mailable()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_get_the_available_template_variables_for_a_mail_template()
    {
        $this->markTestIncomplete();
    }
}
