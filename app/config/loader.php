<?php

/**
 * Registering an autoloader
 * @var $config
 */

use Phalcon\Loader;

$loader = new Loader();

$loader->registerNamespaces([
    'App\Models'       => APP_PATH . '/models/',
    'App\Controllers'  => APP_PATH . '/controllers/',
    'App'              => APP_PATH . '/Library/',
    'App\Transformers' => APP_PATH . '/transformers'
]);
$loader->registerFiles([
    __DIR__ . '/helper.php',
]);
$loader->register();

require_once BASE_PATH . '/vendor/autoload.php';
