<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    */
    'routes' => [
        'api' => [
            'enabled' => true,
            'middleware' => ['api', 'auth:api', 'akk-api', 'permission:mail-template'],
            'prefix' => 'api/v1/accounts/{uuid}/packages/mail-template',
            'as' => 'api.mail-template.'
        ],
        'back' => [
            'enabled' => false,
            'middleware' => ['web', 'auth', 'akk-back', 'permission:mail-template'],
            'prefix' => 'brain/{uuid}/mail-template',
            'as' => 'brain.mail-template.'
        ],
    ],

    'seeds' => [
        'mail-template-type' => 'Mail Template',
        'mail-templates' => [
            [
                'mailable' => \Spatie\MailTemplates\Classes\CustomMailable::class,
                'subject' => 'Welcome Email',
                'html_template' => "<p>Hi {{ name }}, </p> <p>How is going ?</p>",
                'text_template' => "Hi {{ name }}, How is going ?",
                'code' => 'ready_to_send',
                'label' => 'Prêt à être envoyé',
            ]
        ]
    ],
];
