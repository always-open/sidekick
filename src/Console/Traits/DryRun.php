<?php

namespace AlwaysOpen\Sidekick\Console\Traits;

trait DryRun
{
    public const string DRYRUN_SIGNATURE = ' {--dry-run : Whether to discard changes}';

    public function initializeDryRun(): void
    {
        if (! str_contains($this->signature, '--dry-run')) {
            $this->signature .= self::DRYRUN_SIGNATURE;
        }
    }

    protected function isDryRun(): bool
    {
        return $this->option('dry-run');
    }
}
