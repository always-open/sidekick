<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

use InvalidArgumentException;

trait RequiredNumericOption
{
    /**
     * @param string $key
     * @param string $type
     *
     * @return int|float
     */
    protected function requiredNumericOption(string $key, string $type = 'int'): int|float
    {
        $value = $this->option($key);

        if (null === $value) {
            throw new InvalidArgumentException($key . ' is required');
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
