<?php

namespace BluefynInternational\Sidekick\Helpers;

use Illuminate\Database\Query\Builder;

class Query
{
    /**
     * Logic copied from Laravel Telescope.
     *
     * @see https://github.com/laravel/telescope/blob/3.x/src/Watchers/QueryWatcher.php
     *
     * @license https://github.com/laravel/telescope/blob/3.x/LICENSE.md MIT License
     *
     * @param Builder $query For Eloquent Query Builders, pass the output of toBase()
     *
     * @return String
     */
    public static function toString(Builder $query) : String
    {
        $sql = $query->toSql();

        /**
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $pdo = $query->getConnection()->getPdo();
        foreach ($query->getConnection()->prepareBindings($query->getBindings()) as $key => $binding) {
            $regex = is_numeric($key)
                ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/u"
                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/u";

            if ($binding === null) {
                $binding = 'NULL';
            } elseif (! is_int($binding) && ! is_float($binding)) {
                $binding = $pdo->quote($binding);
            }

            $sql = preg_replace($regex, (string) $binding, $sql, 1);
        }

        return \SqlFormatter::format($sql, false);
    }
}
