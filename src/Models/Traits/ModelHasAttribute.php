<?php

namespace AlwaysOpen\Sidekick\Models\Traits;

trait ModelHasAttribute
{
    public function hasAttribute(string $attribute) : bool
    {
        return array_key_exists($attribute, $this->attributesToArray());
    }
}
