<?php
/**
 * Local variables
 * @var Micro $app
 */

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Events\Manager;

$eventsManager = new Manager();
$eventsManager->attach('micro', new AuthenticationMiddleware());
$app->before(new AuthenticationMiddleware());

$users = new MicroCollection();
$users->setHandler(new UsersController());
$users->setPrefix('/users');
$users->get('/', 'list');
$users->get('/{email}', 'item');
$users->post('/', 'add');
$users->put('/{id}', 'update');
$users->post('/avatar', 'avatar');
$users->put('/password', 'password');
$users->get('/me', 'me');
$app->mount($users);

$follow = new MicroCollection();
$follow->setHandler(new FollowController());
$follow->setPrefix('/follow');
$follow->post('/', 'add');
$follow->delete('/', 'delete');
$follow->get('/me', 'me');

$app->mount($follow);

$auth = new MicroCollection();
$auth->setHandler(new AuthController());
$auth->setPrefix('/auth');
$auth->post('/', 'login');
$auth->get('/check', 'check');
$app->mount($auth);
/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
