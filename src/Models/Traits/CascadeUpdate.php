<?php

namespace BluefynInternational\Sidekick\Models\Traits;

use BluefynInternational\Sidekick\Observers\CascadeUpdateObserver;

trait CascadeUpdate
{
    protected static function bootCascadeUpdate()
    {
        parent::observe(CascadeUpdateObserver::class);
    }

    public function getRelationshipsToUpdate() : array
    {
        return [];
    }
}
