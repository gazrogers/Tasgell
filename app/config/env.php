<?php

error_reporting(E_ALL);

/**
 * @const BASE_PATH Document root
 */
define('BASE_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);

/**
 * @const APP_PATH Main application path
 */
define('APP_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR);

/**
 * Set the default locale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Set timezone
 */
date_default_timezone_set('Europe/London');
