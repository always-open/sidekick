<?php

namespace AlwaysOpen\Sidekick\Tests\Traits;

use AlwaysOpen\Sidekick\Observers\CascadeUpdateObserver;
use AlwaysOpen\Sidekick\Observers\OrderedObserver;
use AlwaysOpen\Sidekick\Scopes\OrderedScope;
use AlwaysOpen\Sidekick\Tests\Fakes\CascadeUpdateModel;
use AlwaysOpen\Sidekick\Tests\Fakes\OrderedModel;
use AlwaysOpen\Sidekick\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ObservedTraitsTest extends TestCase
{
    #[Test]
    public function orderedRegistersItsObserverAndScope()
    {
        new OrderedModel();

        foreach (['saving', 'creating', 'updating'] as $event) {
            $this->assertContains(
                OrderedObserver::class.'@'.$event,
                OrderedModel::getEventDispatcher()->getRawListeners()['eloquent.'.$event.': '.OrderedModel::class] ?? [],
            );
        }

        $this->assertTrue(OrderedModel::hasGlobalScope(OrderedScope::class));
    }

    #[Test]
    public function cascadeUpdateRegistersItsObserver()
    {
        new CascadeUpdateModel();

        foreach (['saved', 'deleted'] as $event) {
            $this->assertContains(
                CascadeUpdateObserver::class.'@'.$event,
                CascadeUpdateModel::getEventDispatcher()->getRawListeners()['eloquent.'.$event.': '.CascadeUpdateModel::class] ?? [],
            );
        }
    }
}
