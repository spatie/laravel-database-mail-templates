<?php

namespace Spatie\MailTemplates\Classes;

use App\Models\User;
use Spatie\MailTemplates\TemplateMailable;

class CustomMailable extends TemplateMailable
{
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->name = $user->firstname;
    }
}
