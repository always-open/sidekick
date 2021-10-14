<?php

namespace BluefynInternational\Sidekick\Observers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderedObserver
{
    public function saving(Model $saving)
    {
        if (null === $saving->{$saving::$_orderedColumn}) {
            $saving->{$saving::$_orderedColumn} = $saving::max($saving::$_orderedColumn) + 1;
        }
    }

    public function creating(Model $creating)
    {
        if ($creating->isDirty($creating::$_orderedColumn)) {
            $this->updateImpactedOrderedSiblings($creating);
        }
    }

    public function updating(Model $updating)
    {
        if ($updating->isDirty($updating::$_orderedColumn)) {
            $this->updateImpactedOrderedSiblings($updating);
        }
    }

    protected function updateImpactedOrderedSiblings(Model $model)
    {
        $oldSort = $model->getOriginal($model::$_orderedColumn) ?? PHP_INT_MAX;
        $newSort = $model->{$model::$_orderedColumn};
        $adjustmentAmount = $newSort > $oldSort ? -1 : 1;

        app(get_class($model))->where($model::$_orderedColumn, '>=', min($oldSort, $newSort))
            ->where($model::$_orderedColumn, '<=', max($oldSort, $newSort))
            ->when($model->getKey(), function (Builder $builder) use ($model) {
                $builder->where($model->getKeyName(), '!=', $model->getKey());
            })
            ->each(function (Model $instance) use ($adjustmentAmount) {
                $instance->{$instance::$_orderedColumn} += $adjustmentAmount;
                $instance->saveQuietly();
            });
    }
}
