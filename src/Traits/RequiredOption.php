<?php

namespace BluefynInternational\Sidekick\Traits;

trait RequiredOption
{
    /**
     * @param string $key
     *
     * @throws \InvalidArgumentException
     *
     * @return array|string|bool
     */
    protected function requiredOption(string $key)
    {
        $value = $this->option($key);

        if (null === $value) {
            throw new \InvalidArgumentException($key . ' is required');
        }

        return $value;
    }
}
