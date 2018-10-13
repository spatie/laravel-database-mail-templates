<?php

namespace Spatie\MailTemplates\Tests;

use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\MailTemplates\Tests\stubs\Mails\BasicMail;
use Spatie\Snapshots\MatchesSnapshots;

class MarkdownTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_can_render_markdown()
    {
        $this->markTestSkipped('Cant render markdown mails with components and themes without writing to view file :(');
        MailTemplate::create([
            'mailable' => BasicMail::class,
            'template' => $this->getMarkdownTemplate(),
            'type' => 'markdown',
        ]);

        $renderedMail = (new BasicMail('John'))->render();

        $this->assertMatchesSnapshot($renderedMail);
    }

    /** @test */
    public function it_can_render_markdown_with_a_theme()
    {
        $this->markTestSkipped('Cant render markdown mails with components and themes without writing to view file :(');

        MailTemplate::create([
            'mailable' => BasicMail::class,
            'template' => $this->getMarkdownTemplate(),
            'type' => 'markdown',
            'markdown_theme' => 'peacocks-in-space',
        ]);

        $renderedMail = (new BasicMail('John'))->render();

        $this->assertMatchesSnapshot($renderedMail);
    }

    protected function getMarkdownTemplate(): string
    {
        return <<<md
@component('mail::message')
# Hello, {{name}} 

@component('mail::button', ['url' => 'https://spatie.be'])
Click here!
@endcomponent

@endcomponent
md;
    }
}
