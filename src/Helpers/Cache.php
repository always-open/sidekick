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
                // Sort arrays for consistent encoding
                if (array_keys($keyValues) === range(0, count($keyValues) - 1)) {
                    sort($keyValues);
                } else {
                    ksort($keyValues);
                }
            }

            $key .= md5(json_encode($keyValues));
        }

        if ($key && $suffixes) {
            $key .= '-';
        }

        $key .= implode('-', $suffixes);

        return $key;
    }
}
