<?php

namespace AlwaysOpen\Sidekick\Helpers;

class Arrays
{
    /**
     * @param array  $array1
     * @param array  $array2
     * @param String $column
     *
     * @return array
     */
    public static function uniqueMergeColumn(array $array1, array $array2, String $column) : array
    {
        return array_unique(
            array_merge(
                data_get($array1, $column, []),
                data_get($array2, $column, []),
            ),
            SORT_REGULAR,
        );
    }
}
