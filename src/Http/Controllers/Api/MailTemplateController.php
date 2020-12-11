<?php

namespace Spatie\MailTemplates\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Spatie\MailTemplates\Http\Resources\MailTemplate as MailTemplateResource;
use Spatie\MailTemplates\Http\Resources\MailTemplateCollection;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MailTemplateController extends Controller
{
    public function index($accountUuid)
    {
        return new MailTemplateCollection(
            QueryBuilder::for(MailTemplate::class)
            ->allowedFilters([
                'mailable', 'subject', 'html_template', 'text_template', AllowedFilter::exact('type_id'),
            ])
            ->allowedSorts(['mailable', 'subject', 'html_template', 'text_template'])
            ->allowedIncludes(['type'])
            ->jsonPaginate()
        );
    }

    /**
     * Un exemple de courriel en particulier.
     *
     * @param  $mailTemplateUuid
     * @param  $accountUuid
     * @return MailTemplateResource
     */
    public function show($accountUuid, $mailTemplateUuid)
    {
        $mailTemplate = MailTemplate::where('uuid', $mailTemplateUuid)->first();

        return new MailTemplateResource($mailTemplate);
    }
}
