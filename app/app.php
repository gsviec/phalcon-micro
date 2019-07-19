<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */
use Phalcon\Mvc\Micro\Collection as MicroCollection;
$users = new MicroCollection();
// Set the main handler. ie. a controller instance
$users->setHandler(new UsersController());
// Set a common prefix for all routes
$users->setPrefix('/users');
$users->get('/', 'index');
$users->post('/', 'add');

// Use the method 'show' in usersController
$users->get('/display/{slug}', 'show');

$app->mount($users);
/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});



/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
