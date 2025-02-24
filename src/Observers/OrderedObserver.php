<?php

namespace AlwaysOpen\Sidekick\Observers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderedObserver
{
    /** @psalm-suppress UndefinedMagicPropertyFetch, UndefinedMagicMethod */
    public function saving(Model $model) : void
    {
        if (property_exists($model::class, '_orderedColumn') && null === $model->{$model::$_orderedColumn}) {
            $model->{$model::$_orderedColumn} = $model::max($model::$_orderedColumn) + 1;
        }
    }

    /** @psalm-suppress UndefinedMagicPropertyFetch */
    public function creating(Model $model) : void
    {
        if (property_exists($model::class, '_orderedColumn') && $model->isDirty($model::$_orderedColumn)) {
            $this->updateImpactedOrderedSiblings($model);
        }
    }

    /** @psalm-suppress UndefinedMagicPropertyFetch */
    public function updating(Model $model) : void
    {
        if (property_exists($model::class, '_orderedColumn') && $model->isDirty($model::$_orderedColumn)) {
            $this->updateImpactedOrderedSiblings($model);
        }
    }

    /** @psalm-suppress UndefinedMagicPropertyFetch */
    protected function updateImpactedOrderedSiblings(Model $model) : void
    {
        if (property_exists($model::class, '_orderedColumn')) {
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
}
