<?php

namespace AlwaysOpen\Sidekick;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AlwaysOpen\Sidekick\Sidekick
 */
class SidekickFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Sidekick';
    }
}
