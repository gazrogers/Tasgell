<?php
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Controller\TaskController;

/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Task routes
 */
$tasks = new MicroCollection();
$tasks->setHandler($app->di->get('Model\\BusinessLogic\\Tasks'));
$tasks->setPrefix('/task');
$tasks->post('', 'create');
$tasks->get('{params:(/.*)*}', 'read');
$tasks->put('/{taskId:[0-9]+}/?', 'update');
$tasks->delete('/{taskId:[0-9]+}/?', 'delete');
$app->mount($tasks);

$app->get('/user/[0-9]+/?', function () use($app) {	
    $app->response->setStatusCode(501, "Not Implemented")->sendHeaders();
    echo '';
});
$app->post('/user/update', function () use($app) {	
    $app->response->setStatusCode(501, "Not Implemented")->sendHeaders();
    echo '';
});
$app->post('/user/create', function () use($app) {
    $app->response->setStatusCode(501, "Not Implemented")->sendHeaders();
    echo '';
});
$app->post('/user/delete', function () use($app) {
    $app->response->setStatusCode(501, "Not Implemented")->sendHeaders();
    echo '';
});

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});

/**
 * Error handler
 */
$app->error(function ($exception) use($app) {
    $errorHandler = $app->di->get('Model\\BusinessLogic\\ErrorHandler');
    $errorHandler->handle($exception);
    return false;
});