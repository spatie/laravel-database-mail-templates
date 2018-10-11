<?php

namespace Spatie\MailTemplates\Tests\stubs;

class User
{
    /** @var string */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
