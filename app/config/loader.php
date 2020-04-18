<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    [
        'Controller' => BASE_PATH . $config->application->controllersDir,
        'Model'      => BASE_PATH . $config->application->modelsDir,
        'Library'    => BASE_PATH . $config->application->libraryDir
    ]
)->register();
