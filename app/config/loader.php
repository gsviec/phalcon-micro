<?php

/**
 * Registering an autoloader
 */

use Phalcon\Loader;

$loader = new Loader();

$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->controllersDir
    ]
)->register();
