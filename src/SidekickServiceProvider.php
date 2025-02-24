<?php

namespace AlwaysOpen\Sidekick;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/** @psalm-suppress ClassMustBeFinal */
class SidekickServiceProvider extends PackageServiceProvider
{
    #[\Override]
    public function configurePackage(Package $package): void
    {
        $package->name('Sidekick');
    }
}
