<?php

namespace BluefynInternational\Sidekick\Observers;

use Illuminate\Database\Eloquent\Model;

class OrderedObserver
{
    public function saving(Model $saving)
    {
        if (null === $saving->{$saving::$_orderedColumn}) {
            $saving->{$saving::$_orderedColumn} = $saving::max($saving::$_orderedColumn) + 1;
        }
    }
}
