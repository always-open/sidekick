<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

trait ArrayOption
{
    /**
     * @param string $key
     * @param string $delimiter
     *
     * @return array
     */
    protected function arrayOption(string $key, string $delimiter = ','): array
    {
        $val = $this->option($key);
        if (null === $val) {
            return [];
        }

        if (is_array($val)) {
            return $val;
        }

        return array_filter(explode($delimiter, $val));
    }
}
