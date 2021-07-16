<?php

namespace BluefynInternational\Sidekick\Traits;

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
        return array_filter(explode($delimiter, $this->option($key)));
    }
}
