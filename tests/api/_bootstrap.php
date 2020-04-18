<?php
// Start the built-in server for testing
const WEB_SERVER_HOST = "localhost";
const WEB_SERVER_PORT = "9000";
const WEB_SERVER_ROUTER = __DIR__ . "/../../testrouter.php";
// Command that starts the built-in web server
$command = sprintf(
    'php -S %s:%d %s -c /usr/local/etc/php/php.ini >/dev/null 2>&1 & echo $!',
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    WEB_SERVER_ROUTER
);

// Execute the command and store the process ID
$output = array();
exec($command, $output);
$pid = (int) $output[0];

// Kill the web server when the process ends
register_shutdown_function(function() use ($pid) {
    exec('kill ' . $pid);
});