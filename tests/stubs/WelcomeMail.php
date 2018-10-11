<?php

namespace Spatie\MailTemplates\Tests\stubs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class WelcomeMail extends TemplateMailable
{
    use Queueable, SerializesModels;

    /** @var User */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function getVariables(): array
    {
        return ['name'];
    }

    public function build()
    {
        return $this->with('name', $this->user->name);
    }
}




// Alt version without build - vars can be extracted from public props
class WelcomeMailAlt extends TemplateMailable
{
    use Queueable, SerializesModels;

    /** @var string */
    public $name;

    public function __construct(User $user)
    {
        $this->name = $user->name;
    }
}
