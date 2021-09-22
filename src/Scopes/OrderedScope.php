<?php

namespace BluefynInternational\Sidekick\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderedScope implements Scope
{
    protected string $column;

    protected string $direction;

    public function __construct(string $column = 'sort_order', string $direction = 'asc')
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    /**
     * @param Builder $builder
     * @param Model   $model
     */
    public function apply(Builder $builder, Model $model) : void
    {
        $builder->orderBy($model->getTable() . '.' . $this->column, $this->direction);
    }
}
