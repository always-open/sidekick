<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

use InvalidArgumentException;

trait NumericOption
{
    /**
     * Retrieve an option value and cast it to a numeric type.
     *
     * @param string $key   The option key to retrieve.
     * @param string $type  The numeric type to cast to ('int' or 'float'). Defaults to 'int'.
     * @return int|float|null  The numeric value of the option, or null if not set.
     * @throws InvalidArgumentException If the value is not numeric or if the type is invalid.
     */
    protected function numericOption(string $key, string $type = 'int'): int|float|null
    {
        $value = $this->option($key);

        if (null === $value) {
            return $value;
        }

        if (! is_numeric($value)) {
            throw new InvalidArgumentException($key . ' must be a number.');
        }

        if (! in_array($type, ['int', 'float'])) {
            throw new InvalidArgumentException($type . ' is not valid, must be one of "int", "float".');
        }

        settype($value, $type);

        return $value;
    }
}
