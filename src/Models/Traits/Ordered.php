<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

use AlwaysOpen\Sidekick\Observers\OrderedObserver;
use AlwaysOpen\Sidekick\Scopes\OrderedScope;

trait Ordered
{
    public static string $_orderedColumn = 'sort_order';

    protected static function bootOrdered()
    {
        parent::observe(OrderedObserver::class);
        static::addGlobalScope(new OrderedScope(self::$_orderedColumn));
    }
}
