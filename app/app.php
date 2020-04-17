<?php
use Controller\TaskController;
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});
$app->get('/task/[0-9]+/?', function () use($app) {	
    $app->response->setStatusCode(501, "Not Implemented")->sendHeaders();
    echo '';
});
$app->post('/task/update', function () use($app) {	
    $tasks = $app->di->get('Model\\BusinessLogic\\Tasks');
    $tasks->update($app->request->getRawBody());
});
$app->post('/task/create', function () use($app) {
    $tasks = $app->di->get('Model\\BusinessLogic\\Tasks');
    $tasks->create($app->request->getRawBody());
});
$app->post('/task/delete', function () use($app) {
    $app->response->setStatusCode(501, "Not Implemented")->sendHeaders();
    echo '';
});
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