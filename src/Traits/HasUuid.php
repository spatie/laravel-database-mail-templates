<?php

namespace Spatie\MailTemplates\Traits;

use Webpatser\Uuid\Uuid;

/**
 * Trait HasUuid
 */
trait HasUuid
{

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $uuidFieldName = $model->getUuidFieldName();
            $model->$uuidFieldName = (string) Uuid::generate(4);
        });
    }

    public function getUuidFieldName()
    {
        if (! empty($this->uuidFieldName)) {
            return $this->uuidFieldName;
        }

        return 'uuid';
    }
}
