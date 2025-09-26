<?php

namespace AlwaysOpen\Sidekick\Helpers;

/** @psalm-suppress ClassMustBeFinal */
class Cache
{
    public static function generateCacheKey(mixed $keyValues, array $prefixes = [], array $suffixes = []): string
    {
        $key = implode('-', $prefixes);

        if (null !== $keyValues) {

            if ($key) {
                $key .= '-';
            }

            if (is_array($keyValues)) {
                sort($keyValues);
            }

            $key .= md5(serialize($keyValues));
        }

        if ($key && $suffixes) {
            $key .= '-';
        }

        $key .= implode('-', $suffixes);

        return $key;
    }
}
