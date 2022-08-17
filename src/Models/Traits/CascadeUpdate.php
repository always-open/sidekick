<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

use AlwaysOpen\Sidekick\Observers\CascadeUpdateObserver;

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
