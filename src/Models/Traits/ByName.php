<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

use AlwaysOpen\Sidekick\Helpers\Strings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ByName
{
    use HasCache;

    public static function byName(string $name): static|null
    {
        $cacheEnabled = config('sidekick.traits.cache_enabled');

        if ($cacheEnabled) {
            $key = \AlwaysOpen\Sidekick\Helpers\Cache::generateCacheKey(
                $name,
                prefixes: [
                    'model',
                    Strings::nameFromClass(static::class),
                ]
            );

            return Cache::remember($key, ttl: config('sidekick.traits.cache_ttl'), callback: function () use ($name) {
                return static::firstWhere('name', $name);
            });
        }

        return static::firstWhere('name', $name);
    }

    public static function byNameIn(array $names): Collection
    {
        $cacheEnabled = config('sidekick.traits.cache_enabled');

        if ($cacheEnabled) {
            $key = \AlwaysOpen\Sidekick\Helpers\Cache::generateCacheKey(
                $names,
                prefixes: [
                    'model',
                    Strings::nameFromClass(static::class),
                ]
            );

            return Cache::remember($key, ttl: config('sidekick.traits.cache_ttl'), callback: function () use ($names) {
                return static::whereIn('name', $names)->get();
            });
        }

        return static::whereIn('name', $names)->get();
    }
}
