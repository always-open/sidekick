<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

use Illuminate\Support\Facades\Cache;

trait AtomicDatabaseActions
{
    public static function firstOrCreateAtomic(array $attributes, array $values = []) : static
    {
        return self::atomicDatabaseAction('firstOrCreate', $attributes, $values);
    }

    public static function updateOrCreateAtomic(array $attributes, array $values = []) : static
    {
        return self::atomicDatabaseAction('updateOrCreate', $attributes, $values);
    }

    public static function firstOrNewAtomic(array $attributes, array $values = []) : static
    {
        return self::atomicDatabaseAction('firstOrNew', $attributes, $values);
    }

    public static function updateOrInsertAtomic(array $attributes, array $values = []) : static
    {
        return self::atomicDatabaseAction('updateOrInsert', $attributes, $values);
    }

    protected static function atomicDatabaseAction(string $action, array $attributes, array $values = []) : static
    {
        $cache_key = self::getAtomicCacheKey($attributes);

        return retry(3, function () use ($attributes, $values, $cache_key, $action) {
            $lock = Cache::lock($cache_key, 9);
            $lock->block(3);
            $instance = static::$action($attributes, $values);
            $lock->release();

            return $instance;
        });
    }

    protected static function getAtomicCacheKey(array $attributes) : string
    {
        sort($attributes);

        return get_called_class() . ':' . md5(serialize($attributes));
    }
}
