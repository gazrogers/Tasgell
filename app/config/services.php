<?php

use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Logger\Adapter\File as FileAdapter;

/**
 * Shared configuration service
 */
$di->setShared('config', new ConfigIni(BASE_PATH . '/app/config/config.ini'));

/**
 * Sets the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir(BASE_PATH . $config->application->viewsDir);
    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $dbName = getenv("LOCATION") == "docker-dev-cli" ? $config->databasename->test : $config->databasename->live;

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => getenv("DB_CREDS_USERNAME"),
        'password' => getenv("DB_CREDS_PASSWORD"),
        'dbname'   => $dbName,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

$di->setShared(
    'logger', function () {
        $config = $this->getConfig();
        return new FileAdapter(BASE_PATH . $config->application->logsDir . 'application.log');
    }
);

