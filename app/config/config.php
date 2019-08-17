<?php

use Phalcon\Config;

return new Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => env('DB_HOST'),
        'username' => env('DB_USER'),
        'password' => env('DB_PASSWORD'),
        'dbname' => env('DB_NAME'),
        'charset' => 'utf8',
    ],

    'application' => [
        'viewsDir' => APP_PATH . '/views/',
        'baseUri' => '/',
        'jwtSecret' => env('JWT_SECRET', '12345@Qawesome'),
        'debug' => env('DEBUG', false)
    ]
]);
