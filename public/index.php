<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

try {
    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Set environment constants
     */
    require_once realpath(dirname(dirname(__FILE__))) . '/app/config/env.php';

    /**
     * Include Services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * Include Application
     */
    include APP_PATH . '/app.php';

    /**
     * Handle the request
     */
    $app->handle();

} catch (\Exception $e) {
    $error =  $e->getMessage() . "\n";
    $error .= $e->getTraceAsString();
    $logger = $di->get('logger');
    $logger->error($error);
}
