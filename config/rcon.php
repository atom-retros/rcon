<?php

return [
    'enabled' => (bool) env('RCON_ENABLED', true),
    'host' => env('RCON_HOST', '127.0.0.1'),
    'port' => env('RCON_PORT', 3001),
    'domain' => env('RCON_DOMAIN', AF_INET),
    'type' => env('RCON_TYPE', SOCK_STREAM),
    'protocol' => env('RCON_PROTOCOL', SOL_TCP),
];
