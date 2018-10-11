<?php

namespace Spatie\MailTemplates\Tests;

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
            'template' => 'Hello, {{ name }}'
        ]);

        $renderedMail = (new WelcomeMail($this->user))->render();

        $this->assertEquals('<div>Hello, John Doe</div>', $renderedMail);
    }

    /** @test */
    public function it_throws_an_exception_if_no_mail_template_exists_for_mailable()
    {
        $this->expectException(MissingMailTemplate::class);

        (new WelcomeMail($this->user))->render();
    }
}
