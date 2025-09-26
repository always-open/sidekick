<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

use InvalidArgumentException;

trait RequiredEnumOption
{
    /**
     * @param string $key
     * @param array  $options
     *
     * @throws InvalidArgumentException
     *
     * @return array|string|bool
     */
    protected function requiredEnumOption(string $key, array $options): array|string|bool
    {
        $value = $this->option($key);

        if (null === $value) {
            throw new InvalidArgumentException($key . ' is required');
        }

        if (! in_array($value, $options, true)) {
            throw new InvalidArgumentException($key . ' must be one of ' . implode(', ', $options) . '.');
        }

        return $value;
    }
}
