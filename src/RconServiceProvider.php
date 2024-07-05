<?php

namespace Atom\Rcon;

use Atom\Rcon\Services\RconService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RconServiceProvider extends PackageServiceProvider
{
    /**
     * Register services.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('rcon')
            ->hasConfigFile();
    }

    /**
     * Bootstrap services.
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(
            RconService::class,
            fn () => new RconService
        );
    }
}
