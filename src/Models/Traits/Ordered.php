<?php

namespace BluefynInternational\Sidekick\Models\Traits;

use BluefynInternational\Sidekick\Observers\OrderedObserver;
use BluefynInternational\Sidekick\Scopes\OrderedScope;

trait Ordered
{
    public static string $_orderedColumn = 'sort_order';

    protected static function bootOrdered()
    {
        parent::observe(OrderedObserver::class);
        static::addGlobalScope(new OrderedScope(self::$_orderedColumn));
    }
}
