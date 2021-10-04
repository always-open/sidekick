<?php

namespace BluefynInternational\Sidekick\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CascadeUpdateObserver
{
    public function saved(Model $saved)
    {
        $this->cascadeUpdateTime($saved);
    }

    public function deleted(Model $deleted)
    {
        $this->cascadeUpdateTime($deleted);
    }

    protected function cascadeUpdateTime(Model $model, ?Carbon $updatedAt = null)
    {
        $relationsToUpdate = [];

        if (method_exists($model, 'getRelationshipsToUpdate')) {
            $relationsToUpdate = $model->getRelationshipsToUpdate();
        }

        if (filled($relationsToUpdate)) {
            $updatedAt ??= now();
            $model->load($relationsToUpdate);

            foreach ($relationsToUpdate as $relationship) {
                if ($model->isRelation($relationship)) {
                    /**
                     * @var Model $related
                     */
                    $related = $model->getRelation($relationship);

                    if ($related) {
                        $related[$related::UPDATED_AT] = $updatedAt;
                        $related->saveQuietly();
                        $this->cascadeUpdateTime($related, $updatedAt);
                    }
                }
            }
        }
    }
}
