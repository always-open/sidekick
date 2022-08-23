<?php

namespace AlwaysOpen\Sidekick\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingClosureDispatch;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class DebouncedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected string $_cacheKey;
    protected bool $_debounced = false;

    public static function dispatchAndDebounce(
        ShouldQueue $jobToDebounce,
        int         $minimumMillisecondsToWait,
        int|null    $maximumMillisecondsToWait = null,
    ) : PendingClosureDispatch|PendingDispatch {
        return dispatch(new self(
            $jobToDebounce,
            $minimumMillisecondsToWait,
            $maximumMillisecondsToWait
        ));
    }

    public function __construct(
        public ShouldQueue $_jobToDebounce,
        public int         $_minimumMillisecondsToWait,
        public int|null    $_maximumMillisecondsToWait = null,
    ) {
        $this->calculateCacheKey();
        $lock = tap(Cache::lock($this->getCacheKey(), 5))->block(3);

        try {
            if ($this->debounceExists()) {
                $this->_debounced = true;
            } else {
                $this->setDebounce();
                $this->persistMaximumWaitTime();
            }

            $this->persistMinimumWaitTime();
        } finally {
            $lock->release();
        }
    }

    public function handle()
    {
        if ($this->_debounced) {
            return;
        }

        while (
            ! $this->minimumWaitComplete()
            && ! $this->maximumWaitComplete()
        ) {
            usleep(100000); // Sleep 100 milliseconds between checks
        }

        $this->dispatch($this->_jobToDebounce);
    }

    protected function debounceExists() : bool
    {
        return Cache::has($this->getDebounceKey());
    }

    protected function setDebounce() : string
    {
        return Cache::put($this->getDebounceKey(), true, now()->addMilliseconds($this->getMaximumMillisecondsLeft()));
    }

    protected function getDebounceKey() : string
    {
        return $this->getCacheKey() . ':debounce';
    }

    protected function persistMinimumWaitTime() : void
    {
        $minimum = now()->addMilliseconds($this->_minimumMillisecondsToWait);

        Cache::put($this->getMinimumWaitTimeKey(), $minimum, $minimum->addMinute());
    }

    protected function minimumWaitComplete() : bool
    {
        /**
         * @var Carbon|null $minimum
         */
        $minimum = Cache::get($this->getMinimumWaitTimeKey());

        return ! $minimum || $minimum->isPast();
    }

    protected function getMinimumWaitTimeKey() : string
    {
        return $this->getCacheKey() . ':minimum';
    }

    protected function persistMaximumWaitTime() : void
    {
        $maximum = null;
        if ($this->_maximumMillisecondsToWait) {
            $maximum = now()->addMilliseconds($this->_maximumMillisecondsToWait);
        }

        Cache::put($this->getMaximumWaitTimeKey(), $maximum, $maximum?->addMinute());
    }

    protected function maximumWaitComplete() : bool
    {
        /**
         * @var Carbon|null $maximum
         */
        $maximum = Cache::get($this->getMaximumWaitTimeKey());

        if (! $maximum) {
            return true;
        }

        if ($maximum->isPast()) {
            return true;
        }

        return ! $maximum || $maximum->isPast();
    }

    protected function getMaximumWaitTimeKey() : string
    {
        return $this->getCacheKey() . ':maximum';
    }

    protected function calculateCacheKey() : void
    {
        $this->_cacheKey = sprintf(
            '%s:%s',
            get_class($this->_jobToDebounce),
            spl_object_hash($this->_jobToDebounce),
        );
    }

    public function getCacheKey() : string
    {
        return $this->_cacheKey;
    }

    protected function getWaitMillisecondsLeft() : int
    {
        /**
         * @var Carbon|null $minimum
         * @var Carbon|null $maximum
         */
        $minimum = Cache::get($this->getMinimumWaitTimeKey());
        $maximum = Cache::get($this->getMaximumWaitTimeKey());

        if (! $minimum && ! $maximum) {
            return 0;
        }

        if (! $maximum) {
            return $minimum->diffInMilliseconds(now());
        }

        return min($maximum->diffInMilliseconds(now()), $minimum->diffInMilliseconds(now()));
    }

    protected function getMaximumMillisecondsLeft() : int
    {
        /**
         * @var Carbon|null $minimum
         * @var Carbon|null $maximum
         */
        $minimum = Cache::get($this->getMinimumWaitTimeKey());
        $maximum = Cache::get($this->getMaximumWaitTimeKey());

        if ($minimum && $maximum) {
            return max($maximum->diffInMilliseconds(now()), $minimum->diffInMilliseconds(now()));
        }

        if ($maximum) {
            return $maximum->diffInMilliseconds(now());
        }

        return $minimum->diffInMilliseconds(now());
    }
}
