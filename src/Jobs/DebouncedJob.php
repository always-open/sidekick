<?php

namespace AlwaysOpen\Sidekick\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Cache\Lock;
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
    private static int $MICROSECONDS_SLEEP = 100000;
    public ShouldQueue $_jobToDebounce;
    public int         $_minimumMillisecondsToWait = 0;
    public int|null    $_maximumMillisecondsToWait = null;

    public static function dispatchAndDebounce(
        ShouldQueue $jobToDebounce,
        int         $minimumMillisecondsToWait,
        int|null    $maximumMillisecondsToWait = null,
    ) : PendingClosureDispatch|PendingDispatch {
        $debouncer = new self();

        $debouncer->_jobToDebounce = $jobToDebounce;
        $debouncer->_minimumMillisecondsToWait = $minimumMillisecondsToWait;
        $debouncer->_maximumMillisecondsToWait = $maximumMillisecondsToWait;
        $debouncer->calculateCacheKey();

        $lock = tap(Cache::lock($debouncer->getCacheKey(), 5))->block(3);

        try {
            if ($debouncer->debounceExists()) {
                $debouncer->_debounced = true;
            } else {
                $debouncer->setDebounce();
                $debouncer->persistMaximumWaitTime();
            }

            $debouncer->persistMinimumWaitTime();
        } finally {
            $lock->release();
        }

        return dispatch($debouncer);
    }

    public function handle()
    {
        if ($this->_debounced) {
            return;
        }

        $this->checkAndWaitUntilReady();

        /**
         * @var Lock $lock
         */
        $lock = tap(Cache::lock($this->getCacheKey(), 6))->block(6);

        try {
            dispatch($this->_jobToDebounce);
        } finally {
            Cache::forget($this->getMaximumWaitTimeKey());
            Cache::forget($this->getMinimumWaitTimeKey());
            Cache::forget($this->getDebounceKey());

            $lock->forceRelease();
        }
    }

    public function getWaitTime() : int
    {
        $minimum = Cache::get($this->getMinimumWaitTimeKey()) ?? now();
        $maximum = Cache::get($this->getMaximumWaitTimeKey()) ?? $minimum;

        $now = now();

        $minimumWait = $now->diffInMilliseconds($minimum);
        $maximumWait = $now->diffInMilliseconds($maximum);

        if ($minimumWait < 0) {
            $minimumWait = 0;
        }

        if ($maximumWait < 0) {
            $maximumWait = 0;
        }

        return min($minimumWait, $maximumWait);
    }

    protected function checkAndWaitUntilReady() : void
    {
        while (! $this->getWaitTime() > 0) {
            $this->setDebounce();
            usleep(self::$MICROSECONDS_SLEEP);
        }
    }

    protected function debounceExists() : bool
    {
        return Cache::has($this->getDebounceKey());
    }

    protected function setDebounce() : void
    {
        Cache::put($this->getDebounceKey(), true, now()->addMilliseconds($this->getWaitTime() * 2));
    }

    protected function getDebounceKey() : string
    {
        return $this->getCacheKey() . ':debounce';
    }

    protected function persistMinimumWaitTime() : void
    {
        $configMaxDebounceMilliseconds = config('sidekick.debounced_job_maximum_debounce_milliseconds');
        $millisecondsToWait = $configMaxDebounceMilliseconds ?
            min($configMaxDebounceMilliseconds, $this->_minimumMillisecondsToWait) :
            $this->_minimumMillisecondsToWait;

        $minimum = now()->addMilliseconds($millisecondsToWait);

        Cache::put($this->getMinimumWaitTimeKey(), $minimum, $minimum->addMinute());
    }

    protected function getMinimumWaitTimeKey() : string
    {
        return $this->getCacheKey() . ':minimum';
    }

    protected function persistMaximumWaitTime() : void
    {
        $maximum = null;
        if ($this->_maximumMillisecondsToWait) {
            $configMaxDebounceMilliseconds = config('sidekick.debounced_job_maximum_debounce_milliseconds');
            $millisecondsToWait = $configMaxDebounceMilliseconds ?
                min($configMaxDebounceMilliseconds, $this->_maximumMillisecondsToWait) :
                $this->_maximumMillisecondsToWait;

            $maximum = now()->addMilliseconds($millisecondsToWait);
        }

        Cache::put($this->getMaximumWaitTimeKey(), $maximum, $maximum?->addMinute());
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
            sha1(json_encode($this->_jobToDebounce)),
        );
    }

    public function getCacheKey() : string
    {
        return $this->_cacheKey;
    }
}
