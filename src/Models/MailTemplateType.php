<?php

namespace Spatie\MailTemplates\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MailTemplates\Traits\HasUuid;

class MailTemplateType extends Model
{
    use HasUuid;

    protected $table = 'mail_template_types';

    protected $fillable = ['name'];
}
