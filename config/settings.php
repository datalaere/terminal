<?php

return [
    'key' => base64_encode('62A2AY-3297ZX-1Z6XX3-ZX4Y60'),
    'version' => 1.0,
    'path' => BASE_PATH,
    'date' => 'D M j Y H:i:s',
    'timestamp' => 'Y-m-d H:i:s',
    'unix_timestamp' => 'D M j H:i:s Y',
    'email' => 'root@teleterm.net',
    'errors' => true,
    'public' => BASE_PATH . '/public/',
    'views' =>  BASE_PATH . '/resources/views/',
    'database' => BASE_PATH . '/database/',
    'timezone' => 'UTC',
    'music' => [
        'public/sound/80s_pop.mp3',
        'public/sound/80s_pad.mp3',
        'public/sound/80s_disco.mp3',
        'public/sound/80s_synth.mp3'
    ],
    'db' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'teleterm',
        'username' => 'root',
        'password' => 'mysql',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ],
    'cache' => [
        'enable' => false,
        'ttl' => 30,
        'path'   => BASE_PATH . '/storage/cache', // Make sure this directory exists
    ],
    'whitelist' => [
        '194.45.79.27'
    ]
];