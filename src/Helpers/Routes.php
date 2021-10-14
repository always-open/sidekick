<?php

namespace BluefynInternational\Sidekick\Helpers;

class Routes
{
    public static function toRouteIfBackIsLoop(string $route, array $params = []) : string
    {
        if (url()->previous() !== url()->current()) {
            return url()->previous();
        }

        return route($route, $params);
    }
}
