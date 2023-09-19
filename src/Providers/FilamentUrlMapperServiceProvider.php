<?php

namespace Codedor\FilamentUrlMapper\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUrlMapperServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-url-mapper')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('create_package_table');
    }
}
