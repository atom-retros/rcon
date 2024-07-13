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
        $this->app->bind(
            RconService::class,
            fn () => new RconService
        );
    }
}
