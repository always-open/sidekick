<?php

namespace BluefynInternational\Sidekick;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BluefynInternational\Sidekick\Sidekick
 */
class SidekickFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sidekick';
    }
}
