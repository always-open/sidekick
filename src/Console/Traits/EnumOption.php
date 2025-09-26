<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

use InvalidArgumentException;

trait EnumOption
{
    /**
     * @param string     $key
     * @param array      $options
     * @param mixed|null $default
     *
     * @return array|string|bool|null
     */
    protected function enumOption(string $key, array $options, mixed $default = null): array|string|bool|null
    {
        $value = $this->option($key);

        if (null === $value) {
            return $default;
        }

        if (! in_array($value, $options, true)) {
            throw new InvalidArgumentException($key . ' must be one of ' . implode(', ', $options) . '.');
        }

        return $value;
    }
}
