<?php

/**
 * Registering an autoloader
 */

use Phalcon\Loader;

$loader = new Loader();

$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->controllersDir,
        $config->application->transformersDir
    ]
)->register();

require_once BASE_PATH . '/vendor/autoload.php';