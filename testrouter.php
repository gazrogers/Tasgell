<?php
chdir(__DIR__);
// router used by testing framewrok
if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"]))
{
    return false;
}
else
{
    include __DIR__ . '/c3.php';
    $_GET['_url'] = $_SERVER["REQUEST_URI"]; // chop the '/QMS4' off the beginning of the URL
    $_SERVER['DOCUMENT_ROOT'] = "";
    chdir(__DIR__ . '/public');
    include __DIR__ . '/public/index.php';
}