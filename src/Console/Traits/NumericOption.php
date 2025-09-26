<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

use InvalidArgumentException;

trait NumericOption
{
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
