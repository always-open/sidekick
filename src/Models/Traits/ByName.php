<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

trait ByName
{
    public static function byName(string $name) : ?self
    {
        return static::whereName($name)->first();
    }
}
