<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

trait HasCache
{
    protected static array $_cacheKeys = [];

    public static function addCacheKey(string $key): void
    {
        self::$_cacheKeys[$key] = true;
    }

    public static function removeCacheKey(string $key): void
    {
        unset(self::$_cacheKeys[$key]);
    }

    public static function cacheKeyExists(string $key): bool
    {
        return isset(self::$_cacheKeys[$key]);
    }
}
