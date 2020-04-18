<?php
// router used by testing framewrok
if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"]))
{
    return false;
}
else
{
    include __DIR__ . '/c3.php';
    define('MY_APP_STARTED', true);
    $_GET['_url'] = $_SERVER["REQUEST_URI"];
    $_SERVER['DOCUMENT_ROOT'] = "";
    include __DIR__ . '/public/index.php';
}