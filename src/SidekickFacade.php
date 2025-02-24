<?php

namespace AlwaysOpen\Sidekick;

use Illuminate\Support\Facades\Facade;

/**
* @psalm-suppress ClassMustBeFinal
* @see \AlwaysOpen\Sidekick\Sidekick
*/
class SidekickFacade extends Facade
{
    #[\Override]
    protected static function getFacadeAccessor()
    {
        return 'Sidekick';
    }
}
