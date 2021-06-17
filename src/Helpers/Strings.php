<?php

namespace BluefynInternational\Sidekick\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Strings
{
    public static function nameFromClassPretty(String $className) : String
    {
        return self::pascalToSpaces(self::nameFromClass($className));
    }

    public static function modelClassFromNamePretty(String $name) : String
    {
        return self::modelClassFromName(self::spacesToPascal($name));
    }

    public static function modelClassFromName(?String $name) : String
    {
        if (null === $name) {
            return '';
        }

        $parsedName = $name;

        if (! preg_match('/\\\\?App\\\\Models\\\\/', $name)) {
            $parsedName = '\\App\\Models\\' . $name;
        }

        try {
            $parsedName = get_class(app($parsedName));
        } catch (\Exception $exception) {
            $parsedName = $name;
        }

        return $parsedName;
    }

    public static function spacesToPascal(String $input) : String
    {
        return preg_replace('/ /', '', $input);
    }

    public static function nameFromClass(String $className) : String
    {
        $pos = 0;

        if (false !== ($strpos = strrpos($className, '\\'))) {
            $pos = $strpos + 1;
        }

        return substr($className, $pos);
    }

    public static function pascalToSpaces(String $input) : String
    {
        return preg_replace('/(?<!^)(?<![A-Z])[A-Z]/', ' $0', $input);
    }

    public static function passwordGenerator(int $length = 4) : String
    {
        if ($length < 4) {
            $length = 4;
        }
        $password = '';

        $upper = range('A', 'Z');
        $lower = range('a', 'z');
        $numbers = range('0', '9');
        $special = range('!', ')');
        $neededCharacters = [];

        for ($i = 0; $i < $length; $i++) {
            if (empty($neededCharacters)) {
                $neededCharacters = [
                    $upper,
                    $lower,
                    $numbers,
                    $special,
                ];
            }

            $characterSetIndex = array_rand($neededCharacters);
            $characterSet = $neededCharacters[$characterSetIndex];
            $password .= $characterSet[array_rand($characterSet)];
            unset($neededCharacters[$characterSetIndex]);
        }

        return $password;
    }

    public static function stringIdsToCollection(?String $ids): Collection
    {
        return collect(explode(',', $ids))
            ->map(function ($raw_id) {
                return trim($raw_id);
            })
            ->filter(function ($possible_id) {
                return self::isInt($possible_id);
            })
            ->unique();
    }

    public static function isInt(?String $possible_int): bool
    {
        if (null === $possible_int) {
            return false;
        }

        if (
            Str::contains($possible_int, ',')
            && 0 === preg_match('/^\d{1,3}(,\d{3})*?$/', $possible_int)
        ) {
            // Contains commas but not in proper groupings
            return false;
        }

        $possible_int = str_replace(',', '', $possible_int);

        return $possible_int === (string) (int) $possible_int;
    }
}
