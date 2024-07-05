<?php

namespace Atom\Rcon\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Atom\Rcon\Rcon
 */
class Rcon extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Atom\Rcon\Rcon::class;
    }
}
