<?php

namespace Atom\Rcon;

use Atom\Rcon\Services\RconService;
use Illuminate\Support\ServiceProvider;

class RconServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            path: __DIR__.'/../config/rcon.php',
            key: 'rcon',
        );

        $this->app->bind(
            RconService::class,
            fn () => new RconService
        );
    }
}
