<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
use Phalcon\Config;

return new Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => getenv('DB_HOST'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'dbname' => getenv('DB_NAME'),
        'charset' => 'utf8',
    ],

    'application' => [
        'viewsDir' => APP_PATH . '/views/',
        'baseUri' => '/',
        'jwtSecret' => getenv('JWT_SECRET', '12345@Qawesome'),
        'debug' => getenv('DEBUG', false)
    ]
]);
