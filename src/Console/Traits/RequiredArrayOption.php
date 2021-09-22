<?php

namespace BluefynInternational\Sidekick\Console\Traits;

trait RequiredArrayOption
{
    use RequiredOption;

    /**
     * @param string $key
     * @param string $delimiter
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function requiredArrayOption(string $key, string $delimiter = ','): array
    {
        return array_filter(explode($delimiter, $this->requiredOption($key)));
    }
}
